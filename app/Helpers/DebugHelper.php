<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Symfony\Component\VarDumper\VarDumper;

class DebugHelper
{
    /**
     * Log variable with stack trace
     *
     * @param mixed $variable
     * @param string $level
     * @return void
     */
    public static function log($variable, string $level = 'debug'): void
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];
        $context = [
            'file' => $trace['file'],
            'line' => $trace['line'],
            'variable' => $variable
        ];

        Log::$level(var_export($variable, true), $context);
    }

    /**
     * Dump variable and continue execution
     *
     * @param mixed ...$args
     * @return void
     */
    public static function dump(...$args): void
    {
        foreach ($args as $arg) {
            VarDumper::dump($arg);
        }
    }

    /**
     * Dump variable and die
     *
     * @param mixed ...$args
     * @return void
     */
    public static function dd(...$args): void
    {
        foreach ($args as $arg) {
            VarDumper::dump($arg);
        }
        die(1);
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
            'current' => memory_get_usage($realUsage),
            'peak' => memory_get_peak_usage($realUsage),
            'limit' => ini_get('memory_limit')
        ];
    }

    /**
     * Get execution time
     *
     * @return float
     */
    public static function getExecutionTime(): float
    {
        return microtime(true) - LARAVEL_START;
    }

    /**
     * Log database queries
     *
     * @param bool $enable
     * @return void
     */
    public static function logQueries(bool $enable = true): void
    {
        if ($enable) {
            DB::enableQueryLog();
        } else {
            DB::disableQueryLog();
        }
    }

    /**
     * Get logged database queries
     *
     * @return array
     */
    public static function getQueries(): array
    {
        return DB::getQueryLog();
    }

    /**
     * Clear logged database queries
     *
     * @return void
     */
    public static function clearQueries(): void
    {
        DB::flushQueryLog();
    }

    /**
     * Get cache statistics
     *
     * @return array
     */
    public static function getCacheStats(): array
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
     * Get PHP configuration
     *
     * @param string|null $var
     * @return array|string|false
     */
    public static function getPHPConfig(?string $var = null)
    {
        if ($var) {
            return ini_get($var);
        }

        return ini_get_all();
    }

    /**
     * Get debug backtrace
     *
     * @param int $options
     * @param int $limit
     * @return array
     */
    public static function getBacktrace(int $options = DEBUG_BACKTRACE_PROVIDE_OBJECT, int $limit = 0): array
    {
        return debug_backtrace($options, $limit);
    }

    /**
     * Format exception for logging
     *
     * @param \Throwable $exception
     * @return array
     */
    public static function formatException(\Throwable $exception): array
    {
        return [
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
            'previous' => $exception->getPrevious() ? self::formatException($exception->getPrevious()) : null
        ];
    }

    /**
     * Get defined constants
     *
     * @param string|null $category
     * @return array
     */
    public static function getConstants(?string $category = null): array
    {
        return get_defined_constants($category !== null);
    }

    /**
     * Check if debugging is enabled
     *
     * @return bool
     */
    public static function isDebugEnabled(): bool
    {
        return config('app.debug') === true;
    }
} 