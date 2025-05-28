<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Closure;

class CacheHelper
{
    /**
     * Default cache duration in seconds (24 hours)
     */
    const DEFAULT_DURATION = 86400;

    /**
     * Get or set cache with callback
     *
     * @param string $key
     * @param Closure $callback
     * @param int|null $duration
     * @return mixed
     */
    public static function remember(string $key, Closure $callback, ?int $duration = null)
    {
        return Cache::remember($key, $duration ?? self::DEFAULT_DURATION, $callback);
    }

    /**
     * Get multiple cache keys
     *
     * @param array $keys
     * @return array
     */
    public static function getMultiple(array $keys): array
    {
        return Cache::many($keys);
    }

    /**
     * Set multiple cache keys
     *
     * @param array $values
     * @param int|null $duration
     * @return bool
     */
    public static function setMultiple(array $values, ?int $duration = null): bool
    {
        return Cache::putMany($values, $duration ?? self::DEFAULT_DURATION);
    }

    /**
     * Delete multiple cache keys
     *
     * @param array $keys
     * @return bool
     */
    public static function deleteMultiple(array $keys): bool
    {
        return Cache::deleteMultiple($keys);
    }

    /**
     * Clear cache by tags
     *
     * @param array|string $tags
     * @return bool
     */
    public static function clearByTags($tags): bool
    {
        return Cache::tags((array) $tags)->flush();
    }

    /**
     * Get cache key with prefix
     *
     * @param string $key
     * @param string $prefix
     * @return string
     */
    public static function key(string $key, string $prefix = ''): string
    {
        return $prefix ? "{$prefix}:{$key}" : $key;
    }

    /**
     * Check if cache exists
     *
     * @param string $key
     * @return bool
     */
    public static function has(string $key): bool
    {
        return Cache::has($key);
    }

    /**
     * Get cache statistics
     *
     * @return array
     */
    public static function getStats(): array
    {
        return [
            'driver' => config('cache.default'),
            'store' => Cache::getDefaultDriver(),
            'prefix' => Cache::getPrefix(),
            'has_tags_support' => method_exists(Cache::store(), 'tags')
        ];
    }

    /**
     * Clear expired cache
     *
     * @return bool
     */
    public static function clearExpired(): bool
    {
        if (method_exists(Cache::store(), 'cleanup')) {
            Cache::store()->cleanup();
            return true;
        }
        return false;
    }
} 