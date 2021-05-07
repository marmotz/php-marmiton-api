<?php

namespace tests;

use MarmitonApi\MarmitonApi;
use MarmitonApi\Models\Marmiton;
use MarmitonApi\Models\QueryOptions;
use MarmitonApi\Models\Recipe;
use MarmitonApi\Tools\QueryBuilder;
use PHPUnit\Framework\TestCase;

class MarmitonApiTest extends TestCase
{
    private function assertRecipeIsValid(Recipe $recipe): void
    {
        self::assertNotNull($recipe->getAuthor());
        self::assertNotNull($recipe->getAverageRating());
        self::assertNotNull($recipe->getCookingTime());
        self::assertNotNull($recipe->getCookingType());
        self::assertNotNull($recipe->getCost());
        self::assertNotNull($recipe->getDescription());
        self::assertNotNull($recipe->getDifficulty());
        self::assertNotNull($recipe->getIngredients());
        self::assertNotNull($recipe->getKeywords());
        self::assertNotNull($recipe->getNbPeople());
        self::assertNotNull($recipe->getNbRating());
        self::assertNotNull($recipe->getPreparationTime());
        self::assertNotNull($recipe->getSteps());
        self::assertNotNull($recipe->getTags());
        self::assertNotNull($recipe->getTitle());
        self::assertNotNull($recipe->getTotalTime());
        self::assertNotNull($recipe->getType());
        self::assertNotNull($recipe->getUrl());
    }

    public function testBasicQuery(): void
    {
        $recipes = MarmitonApi::searchRecipes('aqt=soja&rct=1&ttlt=40');

        self::assertCount(QueryOptions::DEFAULT_LIMIT, $recipes);

        foreach ($recipes as $recipe) {
            $this->assertRecipeIsValid($recipe);
        }
    }

    public function testShouldBeAbleToGetMoreThanOnePage(): void
    {
        $limit = 46;
        $recipes = MarmitonApi::searchRecipes('aqt=soja', new QueryOptions(['limit' => $limit]));

        self::assertCount($limit, $recipes);

        foreach ($recipes as $recipe) {
            $this->assertRecipeIsValid($recipe);
        }
    }

    public function testSimpleBuilderRequest(): void
    {
        $query = (new QueryBuilder())
            ->vegetarian()
            ->build();
        $recipes = MarmitonApi::searchRecipes($query);

        self::assertCount(QueryOptions::DEFAULT_LIMIT, $recipes);

        foreach ($recipes as $recipe) {
            $this->assertRecipeIsValid($recipe);
        }
    }

    public function testLessRecipesThanLimit(): void
    {
        $query = (new QueryBuilder())
            ->withTitleContaining('gloubiboulga')
            ->build();
        $recipes = MarmitonApi::searchRecipes($query);

        self::assertCount(3, $recipes);

        foreach ($recipes as $recipe) {
            $this->assertRecipeIsValid($recipe);
        }
    }
}
