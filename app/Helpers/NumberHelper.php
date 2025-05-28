<?php

namespace App\Helpers;

class NumberHelper
{
    /**
     * Format number with decimal places
     *
     * @param float $number
     * @param int $decimals
     * @param string $decimalPoint
     * @param string $thousandsSeparator
     * @return string
     */
    public static function format(float $number, int $decimals = 2, string $decimalPoint = '.', string $thousandsSeparator = ','): string
    {
        return number_format($number, $decimals, $decimalPoint, $thousandsSeparator);
    }

    /**
     * Format number as currency
     *
     * @param float $amount
     * @param string $currency
     * @param string $locale
     * @return string
     */
    public static function formatCurrency(float $amount, string $currency = 'USD', string $locale = 'en_US'): string
    {
        $formatter = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
        return $formatter->formatCurrency($amount, $currency);
    }

    /**
     * Format number as percentage
     *
     * @param float $number
     * @param int $decimals
     * @return string
     */
    public static function formatPercent(float $number, int $decimals = 2): string
    {
        return self::format($number, $decimals) . '%';
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

    /**
     * Round number to nearest interval
     *
     * @param float $number
     * @param float $interval
     * @return float
     */
    public static function roundToInterval(float $number, float $interval = 1.0): float
    {
        return round($number / $interval) * $interval;
    }

    /**
     * Convert number to roman numerals
     *
     * @param int $number
     * @return string
     */
    public static function toRoman(int $number): string
    {
        $map = [
            'M' => 1000,
            'CM' => 900,
            'D' => 500,
            'CD' => 400,
            'C' => 100,
            'XC' => 90,
            'L' => 50,
            'XL' => 40,
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1
        ];

        $result = '';
        foreach ($map as $roman => $value) {
            while ($number >= $value) {
                $result .= $roman;
                $number -= $value;
            }
        }

        return $result;
    }

    /**
     * Convert number to words
     *
     * @param float $number
     * @return string
     */
    public static function toWords(float $number): string
    {
        $formatter = new \NumberFormatter('en', \NumberFormatter::SPELLOUT);
        return $formatter->format($number);
    }

    /**
     * Check if number is between two values
     *
     * @param float $number
     * @param float $min
     * @param float $max
     * @param bool $inclusive
     * @return bool
     */
    public static function isBetween(float $number, float $min, float $max, bool $inclusive = true): bool
    {
        return $inclusive 
            ? ($number >= $min && $number <= $max)
            : ($number > $min && $number < $max);
    }

    /**
     * Get ordinal suffix for number
     *
     * @param int $number
     * @return string
     */
    public static function getOrdinalSuffix(int $number): string
    {
        if (!in_array(($number % 100), [11, 12, 13])) {
            switch ($number % 10) {
                case 1:
                    return 'st';
                case 2:
                    return 'nd';
                case 3:
                    return 'rd';
            }
        }
        return 'th';
    }

    /**
     * Format number as ordinal
     *
     * @param int $number
     * @return string
     */
    public static function formatOrdinal(int $number): string
    {
        return $number . self::getOrdinalSuffix($number);
    }

    /**
     * Calculate percentage
     *
     * @param float $value
     * @param float $total
     * @param int $decimals
     * @return float
     */
    public static function calculatePercentage(float $value, float $total, int $decimals = 2): float
    {
        if ($total == 0) {
            return 0;
        }

        return round(($value * 100) / $total, $decimals);
    }

    /**
     * Format number in scientific notation
     *
     * @param float $number
     * @param int $decimals
     * @return string
     */
    public static function formatScientific(float $number, int $decimals = 2): string
    {
        return sprintf("%.{$decimals}e", $number);
    }
} 