# PHP Marmiton API

A web-scraper made to get recipes from marmiton.org.

It's a https://github.com/SoTrxII/marmiton-api PHP portage.

## Installation

```
composer require marmotz/php-marmiton-api
```

## Usage

```php
<?php

use MarmitonApi\MarmitonApi;
use MarmitonApi\Models\Recipe;
use MarmitonApi\Utils\QueryBuilder;

// A query builder is provided to make complex queries
$query = (new QueryBuilder())
  ->withTitleContaining('soja')
  ->withoutOven()
  ->withPrice(Recipe::PRICE_CHEAP)
  ->takingLessThan(45)
  ->withDifficulty(Recipe::DIFFICULTY_EASY)
  ->build();
// Fetch the recipes
$recipes = MarmitonApi::searchRecipes($query);
```

By default, 10 recipes will be returned.
This number can be changed with the `limit` option.

```php
<?php

use MarmitonApi\MarmitonApi;
use MarmitonApi\Models\QueryOptions;
use MarmitonApi\Utils\QueryBuilder;

$query = (new QueryBuilder())
    ->withTitleContaining('soja')
    ->build();
$recipes = MarmitonApi::searchRecipes($query, new QueryOptions(['limit' => 42]));
```

The result is an array of MarmitonApi\Models\Recipe instances.
