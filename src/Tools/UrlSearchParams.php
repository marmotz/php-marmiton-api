<?php

namespace MarmitonApi\Tools;

use Exception;
use IteratorAggregate;

class UrlSearchParams implements IteratorAggregate
{
    private array $list = [];

    /**
     * @throws Exception
     */
    public function __construct($init = '')
    {
        static::createNewURLSearchParamsObject(
            $this,
            is_string($init) ? preg_replace('/^\\?/u', '', $init) : $init
        );
    }

    /**
     * Returns the serialization of the URLSearchParams object's associated list of name-value pairs.
     */
    public function __toString()
    {
        return self::serializeUrl($this->list);
    }

    /**
     * Appends a new name-value pair whose name is name and value is value, to the list of name-value pairs.
     */
    public function append($name, $value): void
    {
        $this->list[] = [$name, $value];
    }

    /**
     * @throws Exception
     */
    private static function createNewURLSearchParamsObject($query, $init): void
    {
        if (!$query) {
            $query = new static();
        }

        if ($init instanceof URLSearchParams) {
            $query->list = $init->list;
        } elseif (is_array($init)) {
            foreach ($init as $pair) {
                if (count($pair) !== 2) {
                    throw new Exception(
                        'URLSearchParams require name/value tuples when being initialized by a sequence.'
                    );
                }
            }

            $query->list = $init;
        } else {
            $query->list = self::parseUrlString($init);
        }
    }

    /**
     * Removes all name-value pairs whose name is name.
     */
    public function delete($name): void
    {
        array_splice(
            $this->list,
            0,
            count($this->list),
            array_filter(
                $this->list,
                static function ($pair) use ($name) {
                    return $pair[0] !== $name;
                }
            )
        );
    }

    /**
     * Returns the value of the first name-value pair whose name is name, and null if there is no such pair.
     */
    public function get(string $name): ?string
    {
        $value = null;
        foreach ($this->list as $pair) {
            if ($pair[0] === $name) {
                $value = $pair[1];
                break;
            }
        }

        return $value;
    }

    /**
     * Returns the values of all name-value pairs whose name is name, in list order, and the empty sequence otherwise.
     */
    public function getAll(string $name): array
    {
        $values = [];
        foreach ($this->list as $pair) {
            if ($pair[0] === $name) {
                $values[] = $pair[1];
            }
        }

        return $values;
    }

    public function getIterator(): UrlSearchParamsIterator
    {
        return new UrlSearchParamsIterator($this->list);
    }

    /**
     * Returns true if there is a name-value pair whose name is name, and false otherwise.
     */
    public function has(string $name): bool
    {
        return !is_null($this->get($name));
    }

    private static function parseUrlString($input): array
    {
        $tuples = [];
        foreach (explode('&', $input) as $bytes) {
            if ($bytes === '') {
                continue;
            }

            $tuples[] = strpos($bytes, '=') !== false ? explode('=', $bytes, 2) : [$bytes, ''];
        }

        $output = [];
        foreach ($tuples as $tuple) {
            foreach ($tuple as $i => $nameOrValue) {
                $tuple[$i] = urldecode($nameOrValue);
            }

            $output[] = $tuple;
        }

        return $output;
    }

    public static function serializeUrl($tuples): string
    {
        foreach ($tuples as $i => $tuple) {
            $tuples[$i] = implode('=', $tuple);
        }

        return implode('&', $tuples);
    }

    /**
     * If there are any name-value pairs whose name is name, set the value of the first such name-value pair to value
     * and remove the others.
     * Otherwise, append a new name-value pair whose name is name and value is value, to the list of name-value pairs.
     */
    public function set(string $name, $value): void
    {
        $already = false;
        foreach ($this->list as $key => &$pair) {
            if ($pair[0] === $name) {
                if ($already) {
                    unset($this->list[$key]);
                } else {
                    $pair[1] = $value;
                    $already = true;
                }
            }
        }

        unset($pair);

        if ($already) {
            $this->list = array_values($this->list);
            array_splice($this->list, 0, count($this->list), $this->list);
        } else {
            $this->list[] = [$name, $value];
        }
    }
}
