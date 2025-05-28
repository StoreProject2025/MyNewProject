<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class URLHelper
{
    /**
     * Check if URL is valid
     *
     * @param string $url
     * @return bool
     */
    public static function isValid(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Get domain from URL
     *
     * @param string $url
     * @return string|null
     */
    public static function getDomain(string $url): ?string
    {
        if (!self::isValid($url)) {
            return null;
        }

        return parse_url($url, PHP_URL_HOST);
    }

    /**
     * Add query parameters to URL
     *
     * @param string $url
     * @param array $params
     * @return string
     */
    public static function addQueryParams(string $url, array $params): string
    {
        $parsedUrl = parse_url($url);
        
        if (!isset($parsedUrl['query'])) {
            $parsedUrl['query'] = '';
        }

        parse_str($parsedUrl['query'], $queryParams);
        $queryParams = array_merge($queryParams, $params);

        $parsedUrl['query'] = http_build_query($queryParams);

        return self::buildUrl($parsedUrl);
    }

    /**
     * Remove query parameters from URL
     *
     * @param string $url
     * @param array $paramsToRemove
     * @return string
     */
    public static function removeQueryParams(string $url, array $paramsToRemove): string
    {
        $parsedUrl = parse_url($url);
        
        if (!isset($parsedUrl['query'])) {
            return $url;
        }

        parse_str($parsedUrl['query'], $queryParams);
        foreach ($paramsToRemove as $param) {
            unset($queryParams[$param]);
        }

        $parsedUrl['query'] = http_build_query($queryParams);

        return self::buildUrl($parsedUrl);
    }

    /**
     * Get query parameters from URL
     *
     * @param string $url
     * @return array
     */
    public static function getQueryParams(string $url): array
    {
        $parsedUrl = parse_url($url);
        
        if (!isset($parsedUrl['query'])) {
            return [];
        }

        parse_str($parsedUrl['query'], $queryParams);
        return $queryParams;
    }

    /**
     * Check if URL is secure (HTTPS)
     *
     * @param string $url
     * @return bool
     */
    public static function isSecure(string $url): bool
    {
        return Str::startsWith(strtolower($url), 'https://');
    }

    /**
     * Force HTTPS on URL
     *
     * @param string $url
     * @return string
     */
    public static function forceHttps(string $url): string
    {
        if (self::isSecure($url)) {
            return $url;
        }

        return str_replace('http://', 'https://', $url);
    }

    /**
     * Get URL path
     *
     * @param string $url
     * @return string|null
     */
    public static function getPath(string $url): ?string
    {
        return parse_url($url, PHP_URL_PATH);
    }

    /**
     * Clean URL (remove fragments and trailing slashes)
     *
     * @param string $url
     * @return string
     */
    public static function clean(string $url): string
    {
        $url = preg_replace('/#.*$/', '', $url);
        return rtrim($url, '/');
    }

    /**
     * Check if URL is relative
     *
     * @param string $url
     * @return bool
     */
    public static function isRelative(string $url): bool
    {
        return !preg_match('#^https?://#i', $url);
    }

    /**
     * Convert relative URL to absolute
     *
     * @param string $relativeUrl
     * @param string $baseUrl
     * @return string
     */
    public static function toAbsolute(string $relativeUrl, string $baseUrl): string
    {
        if (!self::isRelative($relativeUrl)) {
            return $relativeUrl;
        }

        $baseUrl = rtrim($baseUrl, '/');
        $relativeUrl = ltrim($relativeUrl, '/');

        return $baseUrl . '/' . $relativeUrl;
    }

    /**
     * Build URL from parsed components
     *
     * @param array $parsedUrl
     * @return string
     */
    protected static function buildUrl(array $parsedUrl): string
    {
        $scheme   = isset($parsedUrl['scheme']) ? $parsedUrl['scheme'] . '://' : '';
        $host     = $parsedUrl['host'] ?? '';
        $port     = isset($parsedUrl['port']) ? ':' . $parsedUrl['port'] : '';
        $user     = $parsedUrl['user'] ?? '';
        $pass     = isset($parsedUrl['pass']) ? ':' . $parsedUrl['pass']  : '';
        $pass     = ($user || $pass) ? "$pass@" : '';
        $path     = $parsedUrl['path'] ?? '';
        $query    = isset($parsedUrl['query']) && $parsedUrl['query'] ? '?' . $parsedUrl['query'] : '';
        $fragment = isset($parsedUrl['fragment']) ? '#' . $parsedUrl['fragment'] : '';

        return $scheme . $user . $pass . $host . $port . $path . $query . $fragment;
    }
} 