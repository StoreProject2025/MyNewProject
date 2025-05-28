<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class StringHelper
{
    /**
     * Convert string to slug
     *
     * @param string $string
     * @param string $separator
     * @return string
     */
    public static function slug(string $string, string $separator = '-'): string
    {
        return Str::slug($string, $separator);
    }

    /**
     * Convert string to title case
     *
     * @param string $string
     * @return string
     */
    public static function title(string $string): string
    {
        return Str::title($string);
    }

    /**
     * Truncate string
     *
     * @param string $string
     * @param int $length
     * @param string $end
     * @return string
     */
    public static function truncate(string $string, int $length = 100, string $end = '...'): string
    {
        return Str::limit($string, $length, $end);
    }

    /**
     * Generate excerpt from text
     *
     * @param string $text
     * @param int $length
     * @return string
     */
    public static function excerpt(string $text, int $length = 150): string
    {
        $text = strip_tags($text);
        return static::truncate($text, $length);
    }

    /**
     * Convert camelCase to snake_case
     *
     * @param string $string
     * @return string
     */
    public static function toSnakeCase(string $string): string
    {
        return Str::snake($string);
    }

    /**
     * Convert string to camelCase
     *
     * @param string $string
     * @return string
     */
    public static function toCamelCase(string $string): string
    {
        return Str::camel($string);
    }

    /**
     * Convert string to PascalCase
     *
     * @param string $string
     * @return string
     */
    public static function toPascalCase(string $string): string
    {
        return Str::studly($string);
    }

    /**
     * Convert string to kebab-case
     *
     * @param string $string
     * @return string
     */
    public static function toKebabCase(string $string): string
    {
        return Str::kebab($string);
    }

    /**
     * Remove HTML tags from string
     *
     * @param string $string
     * @param array $allowedTags
     * @return string
     */
    public static function stripHtml(string $string, array $allowedTags = []): string
    {
        if (empty($allowedTags)) {
            return strip_tags($string);
        }

        return strip_tags($string, $allowedTags);
    }

    /**
     * Generate initials from name
     *
     * @param string $name
     * @param int $length
     * @return string
     */
    public static function initials(string $name, int $length = 2): string
    {
        $words = explode(' ', $name);
        $initials = array_map(fn($word) => strtoupper(substr($word, 0, 1)), $words);
        return implode('', array_slice($initials, 0, $length));
    }

    /**
     * Check if string contains another string
     *
     * @param string $haystack
     * @param string|array $needles
     * @param bool $caseSensitive
     * @return bool
     */
    public static function contains(string $haystack, $needles, bool $caseSensitive = false): bool
    {
        if (!$caseSensitive) {
            $haystack = strtolower($haystack);
        }

        foreach ((array) $needles as $needle) {
            if (!$caseSensitive) {
                $needle = strtolower($needle);
            }

            if ($needle !== '' && str_contains($haystack, $needle)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Generate a random string
     *
     * @param int $length
     * @param string $type alpha|numeric|alphanumeric|special
     * @return string
     */
    public static function random(int $length = 16, string $type = 'alphanumeric'): string
    {
        $alpha = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numeric = '0123456789';
        $special = '!@#$%^&*()_+-=[]{}|;:,.<>?';

        switch ($type) {
            case 'alpha':
                $chars = $alpha;
                break;
            case 'numeric':
                $chars = $numeric;
                break;
            case 'special':
                $chars = $alpha . $numeric . $special;
                break;
            default:
                $chars = $alpha . $numeric;
        }

        return substr(str_shuffle(str_repeat($chars, $length)), 0, $length);
    }

    /**
     * Format file size
     *
     * @param int $bytes
     * @param int $precision
     * @return string
     */
    public static function formatFileSize(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        return round($bytes / pow(1024, $pow), $precision) . ' ' . $units[$pow];
    }
} 