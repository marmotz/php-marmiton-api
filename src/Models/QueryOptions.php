<?php

namespace MarmitonApi\Models;

class QueryOptions
{
    public const DEFAULT_LIMIT = 10;

    /**
     * Maximum number of recipes to return
     */
    private int $limit = self::DEFAULT_LIMIT;

    public function __construct(array $options = [])
    {
        if (array_key_exists('limit', $options)) {
            $this->limit = $options['limit'];
        }
    }

    /**
     * @return int|mixed
     */
    public function getLimit()
    {
        return $this->limit;
    }
}
