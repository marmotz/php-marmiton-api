<?php

namespace MarmitonApi\Tools;

use Generator;
use InvalidArgumentException;
use JsonException;
use MarmitonApi\Models\Recipe;
use Symfony\Component\DomCrawler\Crawler;

class RecipesParser
{
    private static function fetchList(Crawler $node, string $listClass): array
    {
        $list = [];

        try {
            $list = $node
                ->filter($listClass)
                ->each(
                    function (Crawler $e) {
                        return self::getCleanText($e);
                    }
                );
        } catch (\Exception $e) {
            // Optional attribute, failing to retrieve it isn't noteworthy
        }

        return $list;
    }

    private static function getCleanText(Crawler $e)
    {
        $text = trim($e->text());

        // Remove all tabs, and linebreaks
        $text = str_replace(
            ["\n", "\r", "\t"],
            '',
            trim($text)
        );

        // Allow only a single space between words
        $text = preg_replace('/\s+/', ' ', $text);

        // If there is no space between the first digit and a word (ingredients)
        // add it
        $text = preg_replace('/(\d)([A-Za-z]{2,})/', '$1 $2', $text);

        return $text;
    }

    private static function loadHtml($url): string
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 6.1; rv:76.0) Gecko/20100101 Firefox/76.0";
        $headers[] = "Pragma: ";
        $headers[] = "Cache-Control: max-age=0";
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);

        // if OPEN_BASEDIR is set, then FollowLocation cant be used...
        $followAllowed = !ini_get('open_basedir');
        if ($followAllowed) {
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        }
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 9);
        curl_setopt($curl, CURLOPT_REFERER, $url);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        curl_setopt($curl, CURLOPT_AUTOREFERER, true);
        curl_setopt($curl, CURLOPT_ENCODING, '');
        $data = curl_exec($curl);
        $status = curl_getinfo($curl);
        curl_close($curl);

        // if redirected, then get that redirected page
        if ($status['http_code'] === 301 || $status['http_code'] === 302) {
            // if we FOLLOWLOCATION was not allowed, then re-get REDIRECTED URL
            if (!$followAllowed) {
                $redirectUrl = null;

                if (!empty($status['redirect_url'])) {
                    // if REDIRECT URL is found in HEADER
                    $redirectUrl = $status['redirect_url'];
                } else {
                    // if REDIRECT URL is found in RESPONSE
                    preg_match('/(Location:|URI:)(.*?)(\r|\n)/si', $data, $m);
                    if (!empty($m[2])) {
                        $redirectUrl = $m[2];
                    } else {
                        // if REDIRECT URL is found in OUTPUT
                        preg_match('/moved\s\<a(.*?)href\=\"(.*?)\"(.*?)here\<\/a\>/si', $data, $m);
                        if (!empty($m[1])) {
                            $redirectUrl = $m[1];
                        }
                    }
                }

                // if URL found, then re-use this function again, for the found url
                if ($redirectUrl) {
                    return self::loadHtml(trim($redirectUrl));
                }
            }
        }
        elseif ($status['http_code'] !== 200) {
            // if not redirected and nor "status 200" page, then error...
            throw new InvalidArgumentException("Error {$status['http_code']} while fetching $url");
        }

        return $data;
    }

    /**
     * Format a list string to array
     */
    private static function parseList(string $listString): array
    {
        $list = explode(',', $listString);

        foreach ($list as $key => $item) {
            $list[$key] = trim($item);

            if (!$list[$key]) {
                unset($list[$key]);
            }
        }

        $list = array_unique(array_values($list));

        return $list;
    }

    /**
     * @throws JsonException
     */
    private static function parseRecipe(array $recipeData): Recipe
    {
        $recipe = (new Recipe())
            ->setTitle($recipeData['title'])
            ->setUrl($recipeData['url'])
            ->setImage($recipeData['image'] ? $recipeData['image']['pictureUrls']['origin'] : null)
            ->setAverageRating($recipeData['averageRating'])
            ->setNbRating($recipeData['nbRating'])
            ->setType($recipeData['dishType'])
            ->setTags($recipeData['tags'])
            ->setDifficulty($recipeData['difficulty'])
            ->setCost($recipeData['cost'])
            ->setCookingType($recipeData['cookingType'])
            ->setIsGlutenFree($recipeData['isGlutenFree'])
            ->setIsLactoseFree($recipeData['isLactoseFree'])
            ->setIsVegetarian($recipeData['isVegetarian'])
            ->setIsVegan($recipeData['isVegan'])
            ->setIsPorkFree($recipeData['isPorkFree'])
            ->setIsSweet($recipeData['isSweet'])
            ->setIsSalty($recipeData['isSalty'])
            ->setIsSeasonal($recipeData['isSeasonal'])
            ->setNutriScore($recipeData['nutriScore']);

        // Load the recipe page
        $html = self::loadHtml($recipe->getFullUrl());

        // Search recipe json
        preg_match(
            '|<script type="application&#x2F;ld&#x2B;json">\s*({"@context":"http://schema\.org","@type":"Recipe".*})\s*</script>|Us',
            $html,
            $match
        );

        if (!isset($match[1])) {
            throw new InvalidArgumentException("Unable to load recipe from {$recipe->getFullUrl()}");
        }

        $json = json_decode($match[1], true, 512, JSON_THROW_ON_ERROR);

        $recipe
            ->setCategory($json['recipeCategory'])
            ->setPreparationTime(self::parseTime($json['prepTime']))
            ->setCookingTime(self::parseTime($json['cookTime']))
            ->setTotalTime(self::parseTime($json['totalTime']))
            ->setNbPeople((int) $json['recipeYield'])
            ->setIngredients($json['recipeIngredient'])
            ->setSteps($json['recipeInstructions'])
            ->setAuthor($json['author'])
            ->setDescription($json['description'])
            ->setKeywords(self::parseList($json['keywords']));

        return $recipe;
    }

    public static function parseSearchResults(string $url): Generator
    {
        $html = self::loadHtml($url);

        preg_match(
            '|<script id="__NEXT_DATA__" type="application/json">(.*)</script>|Us',
            $html,
            $match
        );

        try {
            $json = json_decode($match[1], true, 512, JSON_THROW_ON_ERROR);

            foreach ($json['props']['pageProps']['searchResults']['hits'] as $recipeData) {
                try {
                    yield self::parseRecipe($recipeData);
                } catch (InvalidArgumentException $e) {
                }
            }
        } catch (JsonException $e) {
        }
    }

    /**
     * Format a string to minutes
     */
    private static function parseTime(string $time): int
    {
        preg_match('/(\d+)/', $time, $match);

        return $match[1];
    }
}
