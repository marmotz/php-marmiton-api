<?php

namespace MarmitonApi;

use InvalidArgumentException;
use MarmitonApi\Models\Marmiton;
use MarmitonApi\Models\QueryOptions;
use MarmitonApi\Tools\RecipesParser;

class MarmitonApi {
    public static function searchRecipes(string $qs, QueryOptions $options = null): array {
        $options = $options ?: new QueryOptions();

        $page = 1;
        $recipes = [];
        do {
            $url = sprintf(
                '%s%s?%s&page=%d',
                Marmiton::BASE_URL,
                Marmiton::ENDPOINT_QUERY,
                $qs,
                $page
            );

            try {
                foreach (RecipesParser::parseSearchResults($url) as $recipe) {
                    $recipes[] = $recipe;

                    if (count($recipes) === $options->getLimit()) {
                        break;
                    }
                }
            } catch (InvalidArgumentException $e) {
                // next page is 404
                break;
            }

            $page++;
        } while (count($recipes) < $options->getLimit());

        return $recipes;
    }
}
