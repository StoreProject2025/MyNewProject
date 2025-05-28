<?php

namespace App\Helpers;

use Carbon\Carbon;
use DateTimeZone;

class DateTimeHelper
{
    /**
     * Get list of all available timezones
     *
     * @return array
     */
    public static function getTimezones(): array
    {
        return DateTimeZone::listIdentifiers();
    }

    /**
     * Format date to human readable format
     *
     * @param string|Carbon $date
     * @param string $format
     * @return string
     */
    public static function formatDate($date, string $format = 'Y-m-d H:i:s'): string
    {
        if (!$date instanceof Carbon) {
            $date = Carbon::parse($date);
        }

        return $date->format($format);
    }

    /**
     * Get human readable time difference
     *
     * @param string|Carbon $date
     * @return string
     */
    public static function timeAgo($date): string
    {
        if (!$date instanceof Carbon) {
            $date = Carbon::parse($date);
        }

        return $date->diffForHumans();
    }

    /**
     * Check if date is in the future
     *
     * @param string|Carbon $date
     * @return bool
     */
    public static function isFuture($date): bool
    {
        if (!$date instanceof Carbon) {
            $date = Carbon::parse($date);
        }

        return $date->isFuture();
    }

    /**
     * Check if date is in the past
     *
     * @param string|Carbon $date
     * @return bool
     */
    public static function isPast($date): bool
    {
        if (!$date instanceof Carbon) {
            $date = Carbon::parse($date);
        }

        return $date->isPast();
    }

    /**
     * Get start and end of date range
     *
     * @param string $range day|week|month|year
     * @return array
     */
    public static function getDateRange(string $range): array
    {
        $now = Carbon::now();

        switch ($range) {
            case 'day':
                $start = $now->startOfDay();
                $end = $now->copy()->endOfDay();
                break;
            case 'week':
                $start = $now->startOfWeek();
                $end = $now->copy()->endOfWeek();
                break;
            case 'month':
                $start = $now->startOfMonth();
                $end = $now->copy()->endOfMonth();
                break;
            case 'year':
                $start = $now->startOfYear();
                $end = $now->copy()->endOfYear();
                break;
            default:
                throw new \InvalidArgumentException('Invalid range specified');
        }

        return [
            'start' => $start->format('Y-m-d H:i:s'),
            'end' => $end->format('Y-m-d H:i:s')
        ];
    }

    /**
     * Convert date to specified timezone
     *
     * @param string|Carbon $date
     * @param string $timezone
     * @return Carbon
     */
    public static function convertTimezone($date, string $timezone): Carbon
    {
        if (!$date instanceof Carbon) {
            $date = Carbon::parse($date);
        }

        return $date->setTimezone($timezone);
    }

    /**
     * Get age from date
     *
     * @param string|Carbon $date
     * @return int
     */
    public static function getAge($date): int
    {
        if (!$date instanceof Carbon) {
            $date = Carbon::parse($date);
        }

        return $date->age;
    }
} 