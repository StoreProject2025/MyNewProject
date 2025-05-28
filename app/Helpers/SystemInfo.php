<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class SystemInfo
{
    /**
     * Get Laravel version
     *
     * @return string
     */
    public static function getLaravelVersion(): string
    {
        return app()->version();
    }

    /**
     * Get PHP version
     *
     * @return string
     */
    public static function getPHPVersion(): string
    {
        return PHP_VERSION;
    }

    /**
     * Get database information
     *
     * @return array
     */
    public static function getDatabaseInfo(): array
    {
        try {
            return [
                'driver' => config('database.default'),
                'version' => DB::select('select version() as version')[0]->version,
                'database' => config('database.connections.' . config('database.default') . '.database'),
                'tables' => count(Schema::getAllTables())
            ];
        } catch (\Exception $e) {
            return [
                'driver' => config('database.default'),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get server information
     *
     * @return array
     */
    public static function getServerInfo(): array
    {
        return [
            'os' => PHP_OS,
            'software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'hostname' => gethostname(),
            'ip' => $_SERVER['SERVER_ADDR'] ?? '127.0.0.1',
            'protocol' => $_SERVER['SERVER_PROTOCOL'] ?? 'Unknown',
            'time' => date('Y-m-d H:i:s'),
            'timezone' => date_default_timezone_get()
        ];
    }

    /**
     * Get memory usage
     *
     * @param bool $realUsage
     * @return array
     */
    public static function getMemoryUsage(bool $realUsage = false): array
    {
        return [
            'current' => self::formatBytes(memory_get_usage($realUsage)),
            'peak' => self::formatBytes(memory_get_peak_usage($realUsage))
        ];
    }

    /**
     * Get disk usage
     *
     * @param string $directory
     * @return array
     */
    public static function getDiskUsage(string $directory = '/'): array
    {
        return [
            'free' => self::formatBytes(disk_free_space($directory)),
            'total' => self::formatBytes(disk_total_space($directory)),
            'used' => self::formatBytes(disk_total_space($directory) - disk_free_space($directory))
        ];
    }

    /**
     * Get cache information
     *
     * @return array
     */
    public static function getCacheInfo(): array
    {
        return [
            'driver' => config('cache.default'),
            'store' => get_class(Cache::store()),
            'prefix' => config('cache.prefix')
        ];
    }

    /**
     * Get loaded PHP extensions
     *
     * @return array
     */
    public static function getLoadedExtensions(): array
    {
        return get_loaded_extensions();
    }

    /**
     * Get environment information
     *
     * @return array
     */
    public static function getEnvironmentInfo(): array
    {
        return [
            'environment' => app()->environment(),
            'debug' => config('app.debug'),
            'url' => config('app.url'),
            'timezone' => config('app.timezone'),
            'locale' => config('app.locale'),
            'maintenance_mode' => app()->isDownForMaintenance()
        ];
    }

    /**
     * Check if a PHP extension is loaded
     *
     * @param string $extension
     * @return bool
     */
    public static function hasExtension(string $extension): bool
    {
        return extension_loaded($extension);
    }

    /**
     * Get system load average
     *
     * @return array|null
     */
    public static function getLoadAverage(): ?array
    {
        if (function_exists('sys_getloadavg')) {
            $load = sys_getloadavg();
            return [
                '1min' => $load[0],
                '5min' => $load[1],
                '15min' => $load[2]
            ];
        }

        return null;
    }

    /**
     * Format bytes to human readable format
     *
     * @param int $bytes
     * @param int $precision
     * @return string
     */
    protected static function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        return round($bytes / pow(1024, $pow), $precision) . ' ' . $units[$pow];
    }

    /**
     * Get all system information
     *
     * @return array
     */
    public static function getAllInfo(): array
    {
        return [
            'laravel_version' => self::getLaravelVersion(),
            'php_version' => self::getPHPVersion(),
            'database' => self::getDatabaseInfo(),
            'server' => self::getServerInfo(),
            'memory' => self::getMemoryUsage(),
            'disk' => self::getDiskUsage(),
            'cache' => self::getCacheInfo(),
            'environment' => self::getEnvironmentInfo(),
            'load_average' => self::getLoadAverage(),
            'extensions' => self::getLoadedExtensions()
        ];
    }
} 