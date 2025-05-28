<?php

namespace App\Helpers;

use Illuminate\Support\Collection;
use Illuminate\Support\Arr;

class ArrayHelper
{
    /**
     * Convert array to collection
     *
     * @param array $array
     * @return Collection
     */
    public static function toCollection(array $array): Collection
    {
        return collect($array);
    }

    /**
     * Get value from array using dot notation
     *
     * @param array $array
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(array $array, string $key, $default = null)
    {
        return Arr::get($array, $key, $default);
    }

    /**
     * Set value in array using dot notation
     *
     * @param array $array
     * @param string $key
     * @param mixed $value
     * @return array
     */
    public static function set(array $array, string $key, $value): array
    {
        return Arr::set($array, $key, $value);
    }

    /**
     * Check if key exists in array using dot notation
     *
     * @param array $array
     * @param string|array $keys
     * @return bool
     */
    public static function has(array $array, $keys): bool
    {
        return Arr::has($array, $keys);
    }

    /**
     * Remove item from array using dot notation
     *
     * @param array $array
     * @param string|array $keys
     * @return array
     */
    public static function forget(array $array, $keys): array
    {
        Arr::forget($array, $keys);
        return $array;
    }

    /**
     * Get a subset of the array
     *
     * @param array $array
     * @param array|string $keys
     * @return array
     */
    public static function only(array $array, $keys): array
    {
        return Arr::only($array, $keys);
    }

    /**
     * Get all items except specified keys
     *
     * @param array $array
     * @param array|string $keys
     * @return array
     */
    public static function except(array $array, $keys): array
    {
        return Arr::except($array, $keys);
    }

    /**
     * Flatten a multi-dimensional array
     *
     * @param array $array
     * @param int $depth
     * @return array
     */
    public static function flatten(array $array, int $depth = INF): array
    {
        return Arr::flatten($array, $depth);
    }

    /**
     * Group array by key
     *
     * @param array $array
     * @param string $key
     * @return array
     */
    public static function groupBy(array $array, string $key): array
    {
        return collect($array)->groupBy($key)->all();
    }

    /**
     * Sort array by key
     *
     * @param array $array
     * @param string $key
     * @param string $direction
     * @return array
     */
    public static function sortBy(array $array, string $key, string $direction = 'asc'): array
    {
        $collection = collect($array);
        return $direction === 'desc' 
            ? $collection->sortByDesc($key)->values()->all()
            : $collection->sortBy($key)->values()->all();
    }

    /**
     * Filter array by callback
     *
     * @param array $array
     * @param callable $callback
     * @return array
     */
    public static function filter(array $array, callable $callback): array
    {
        return collect($array)->filter($callback)->all();
    }

    /**
     * Map array using callback
     *
     * @param array $array
     * @param callable $callback
     * @return array
     */
    public static function map(array $array, callable $callback): array
    {
        return collect($array)->map($callback)->all();
    }

    /**
     * Convert array to query string
     *
     * @param array $array
     * @return string
     */
    public static function toQueryString(array $array): string
    {
        return http_build_query($array);
    }

    /**
     * Convert array to JSON
     *
     * @param array $array
     * @param int $options
     * @return string
     */
    public static function toJson(array $array, int $options = 0): string
    {
        return json_encode($array, $options);
    }

    /**
     * Find duplicates in array
     *
     * @param array $array
     * @param string|null $key
     * @return array
     */
    public static function findDuplicates(array $array, ?string $key = null): array
    {
        if ($key) {
            $values = array_column($array, $key);
        } else {
            $values = $array;
        }

        return array_unique(array_diff_assoc($values, array_unique($values)));
    }

    /**
     * Remove null values from array
     *
     * @param array $array
     * @param bool $recursive
     * @return array
     */
    public static function removeNull(array $array, bool $recursive = false): array
    {
        return collect($array)->filter(function ($value) use ($recursive) {
            if (is_array($value) && $recursive) {
                return !empty(static::removeNull($value, true));
            }
            return $value !== null;
        })->all();
    }
} 