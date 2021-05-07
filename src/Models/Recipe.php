<?php

namespace MarmitonApi\Models;

class Recipe
{
    const PRICE_CHEAP = 1;
    const PRICE_MEDIUM = 2;
    const PRICE_EXPENSIVE = 3;

    const DIFFICULTY_VERY_EASY = 1;
    const DIFFICULTY_EASY = 2;
    const DIFFICULTY_MEDIUM = 3;
    const DIFFICULTY_HARD = 4;

    const TYPE_STARTER = 'entree';
    const TYPE_MAIN_COURSE = 'platprincipal';
    const TYPE_DESSERT = 'dessert';
    const TYPE_SIDE_DISH = 'accompagnement';
    const TYPE_SAUCE = 'sauce';
    const TYPE_BEVERAGE = 'boisson';
    const TYPE_CANDY = 'confiserie';
    const TYPE_ADVICE = 'conseil';

    /**
     * Title of recipe
     */
    private string $title;

    /**
     * Short description provided by the recipe author
     */
    private string $description;

    /**
     * Link to the recipe on Marmiton's site
     */
    private string $url;

    /**
     * Average of user reviews (up to 5.0)
     */
    private float $averageRating;

    /**
     * Number of user reviews
     */
    private int $nbRating;

    /**
     * Type of recipe
     */
    private string $type;

    /**
     * Category of recipe
     */
    private ?string $category;

    /**
     * Image provided with the recipe. Often the final product.
     */
    private ?string $image;

    /**
     * Tags associated with the recipe
     */
    private array $tags;

    /**
     * Keywords associated with the recipe
     */
    private array $keywords;

    /**
     * Estimated difficulty of making this recipe. On a scale from 1 (very easy) to 4 (hard).
     */
    private int $difficulty;

    /**
     * Cost of recipe. From 1 (Cheap) to 3 (Expensive)
     */
    private int $cost;

    /**
     * Recipe uploader. Can be an username, "anonymous" or "undefined" (admin-provided recipe ?)
     */
    private string $author;

    /**
     * How many people
     */
    private ?int $nbPeople;

    /**
     * Stuff required to make the recipe
     */
    private array $ingredients;

    /**
     * Time required to make the recipe
     */
    private int $preparationTime;

    /**
     * Time required to cook the recipe
     */
    private int $cookingTime;

    /**
     * Time required to make and cook the recipe
     */
    private int $totalTime;

    /**
     * Type of cooking
     */
    private string $cookingType;

    /**
     * All the steps to follow to make the recipe
     */
    private array $steps;

    /**
     * Recipe is without gluten
     */
    private bool $isGlutenFree;

    /**
     * Recipe is without lactose
     */
    private bool $isLactoseFree;

    /**
     * Recipe is for vegetarian
     */
    private bool $isVegetarian;

    /**
     * Recipe is for vegan
     */
    private bool $isVegan;

    /**
     * Recipe is without pork
     */
    private bool $isPorkFree;

    /**
     * Recipe is sweet
     */
    private bool $isSweet;

    /**
     * Recipe is with salt
     */
    private bool $isSalty;

    /**
     * Recipe is seasonal
     */
    private bool $isSeasonal;

    /**
     * NutriScore of recipe. From "A" (good) to "E" (bad)
     */
    private ?string $nutriScore;

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @param string $author
     *
     * @return Recipe
     */
    public function setAuthor(string $author): Recipe
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return float
     */
    public function getAverageRating(): float
    {
        return $this->averageRating;
    }

    /**
     * @param float $averageRating
     *
     * @return Recipe
     */
    public function setAverageRating(float $averageRating): Recipe
    {
        $this->averageRating = $averageRating;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCategory(): ?string
    {
        return $this->category;
    }

    /**
     * @param string|null $category
     *
     * @return Recipe
     */
    public function setCategory(?string $category): Recipe
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return int
     */
    public function getCookingTime(): int
    {
        return $this->cookingTime;
    }

    /**
     * @param int $cookingTime
     *
     * @return Recipe
     */
    public function setCookingTime(int $cookingTime): Recipe
    {
        $this->cookingTime = $cookingTime;

        return $this;
    }

    /**
     * @return string
     */
    public function getCookingType(): string
    {
        return $this->cookingType;
    }

    /**
     * @param string $cookingType
     *
     * @return Recipe
     */
    public function setCookingType(string $cookingType): Recipe
    {
        $this->cookingType = $cookingType;

        return $this;
    }

    /**
     * @return int
     */
    public function getCost(): int
    {
        return $this->cost;
    }

    /**
     * @param int $cost
     *
     * @return Recipe
     */
    public function setCost(int $cost): Recipe
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return Recipe
     */
    public function setDescription(string $description): Recipe
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return int
     */
    public function getDifficulty(): int
    {
        return $this->difficulty;
    }

    /**
     * @param int $difficulty
     *
     * @return Recipe
     */
    public function setDifficulty(int $difficulty): Recipe
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    /**
     * @return string
     */
    public function getFullUrl(): string
    {
        return Marmiton::BASE_URL . $this->url;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @param string|null $image
     *
     * @return Recipe
     */
    public function setImage(?string $image): Recipe
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return array
     */
    public function getIngredients(): array
    {
        return $this->ingredients;
    }

    /**
     * @param array $ingredients
     *
     * @return Recipe
     */
    public function setIngredients(array $ingredients): Recipe
    {
        $this->ingredients = $ingredients;

        return $this;
    }

    /**
     * @return array
     */
    public function getKeywords(): array
    {
        return $this->keywords;
    }

    /**
     * @param array $keywords
     *
     * @return Recipe
     */
    public function setKeywords(array $keywords): Recipe
    {
        $this->keywords = $keywords;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getNbPeople(): ?int
    {
        return $this->nbPeople;
    }

    /**
     * @param int|null $nbPeople
     *
     * @return Recipe
     */
    public function setNbPeople(?int $nbPeople): Recipe
    {
        $this->nbPeople = $nbPeople;

        return $this;
    }

    /**
     * @return int
     */
    public function getNbRating(): int
    {
        return $this->nbRating;
    }

    /**
     * @param int $nbRating
     *
     * @return Recipe
     */
    public function setNbRating(int $nbRating): Recipe
    {
        $this->nbRating = $nbRating;

        return $this;
    }

    /**
     * @return string
     */
    public function getNutriScore(): ?string
    {
        return $this->nutriScore;
    }

    /**
     * @param string $nutriScore
     *
     * @return Recipe
     */
    public function setNutriScore(?string $nutriScore): Recipe
    {
        $this->nutriScore = $nutriScore ? strtoupper($nutriScore) : null;

        return $this;
    }

    /**
     * @return int
     */
    public function getPreparationTime(): int
    {
        return $this->preparationTime;
    }

    /**
     * @param int $preparationTime
     *
     * @return Recipe
     */
    public function setPreparationTime(int $preparationTime): Recipe
    {
        $this->preparationTime = $preparationTime;

        return $this;
    }

    /**
     * @return array
     */
    public function getSteps(): array
    {
        return $this->steps;
    }

    /**
     * @param array $steps
     *
     * @return Recipe
     */
    public function setSteps(array $steps): Recipe
    {
        $this->steps = $steps;

        return $this;
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @param array $tags
     *
     * @return Recipe
     */
    public function setTags(array $tags): Recipe
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return Recipe
     */
    public function setTitle(string $title): Recipe
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return int
     */
    public function getTotalTime(): int
    {
        return $this->totalTime;
    }

    /**
     * @param int $totalTime
     *
     * @return Recipe
     */
    public function setTotalTime(int $totalTime): Recipe
    {
        $this->totalTime = $totalTime;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return Recipe
     */
    public function setType(string $type): Recipe
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return Recipe
     */
    public function setUrl(string $url): Recipe
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return bool
     */
    public function isGlutenFree(): bool
    {
        return $this->isGlutenFree;
    }

    /**
     * @param bool $isGlutenFree
     *
     * @return Recipe
     */
    public function setIsGlutenFree(bool $isGlutenFree): Recipe
    {
        $this->isGlutenFree = $isGlutenFree;

        return $this;
    }

    /**
     * @return bool
     */
    public function isLactoseFree(): bool
    {
        return $this->isLactoseFree;
    }

    /**
     * @param bool $isLactoseFree
     *
     * @return Recipe
     */
    public function setIsLactoseFree(bool $isLactoseFree): Recipe
    {
        $this->isLactoseFree = $isLactoseFree;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPorkFree(): bool
    {
        return $this->isPorkFree;
    }

    /**
     * @param bool $isPorkFree
     *
     * @return Recipe
     */
    public function setIsPorkFree(bool $isPorkFree): Recipe
    {
        $this->isPorkFree = $isPorkFree;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSalty(): bool
    {
        return $this->isSalty;
    }

    /**
     * @param bool $isSalty
     *
     * @return Recipe
     */
    public function setIsSalty(bool $isSalty): Recipe
    {
        $this->isSalty = $isSalty;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSeasonal(): bool
    {
        return $this->isSeasonal;
    }

    /**
     * @param bool $isSeasonal
     *
     * @return Recipe
     */
    public function setIsSeasonal(bool $isSeasonal): Recipe
    {
        $this->isSeasonal = $isSeasonal;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSweet(): bool
    {
        return $this->isSweet;
    }

    /**
     * @param bool $isSweet
     *
     * @return Recipe
     */
    public function setIsSweet(bool $isSweet): Recipe
    {
        $this->isSweet = $isSweet;

        return $this;
    }

    /**
     * @return bool
     */
    public function isVegan(): bool
    {
        return $this->isVegan;
    }

    /**
     * @param bool $isVegan
     *
     * @return Recipe
     */
    public function setIsVegan(bool $isVegan): Recipe
    {
        $this->isVegan = $isVegan;

        return $this;
    }

    /**
     * @return bool
     */
    public function isVegetarian(): bool
    {
        return $this->isVegetarian;
    }

    /**
     * @param bool $isVegetarian
     *
     * @return Recipe
     */
    public function setIsVegetarian(bool $isVegetarian): Recipe
    {
        $this->isVegetarian = $isVegetarian;

        return $this;
    }
}
