<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RequestLogger
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Start time of the request
        $startTime = microtime(true);

        // Process the request
        $response = $next($request);

        // End time of the request
        $endTime = microtime(true);

        // Calculate duration
        $duration = ($endTime - $startTime) * 1000; // Convert to milliseconds

        // Log the request details
        $this->logRequest($request, $response, $duration);

        return $response;
    }

    /**
     * Log request details
     *
     * @param Request $request
     * @param Response $response
     * @param float $duration
     * @return void
     */
    protected function logRequest(Request $request, $response, float $duration): void
    {
        // Skip logging for specific paths
        if ($this->shouldSkipLogging($request)) {
            return;
        }

        $logData = [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'path' => $request->path(),
            'status' => $response->getStatusCode(),
            'duration' => round($duration, 2) . 'ms',
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => $request->user()?->id,
            'headers' => $this->filterHeaders($request->headers->all()),
            'query' => $request->query(),
            'request_id' => $request->header('X-Request-ID') ?? uniqid()
        ];

        // Add request body for non-GET requests
        if (!$request->isMethod('GET')) {
            $logData['body'] = $this->filterRequestData($request->all());
        }

        // Add response data for error status codes
        if ($response->getStatusCode() >= 400) {
            $logData['response'] = $this->getResponseContent($response);
        }

        // Log with appropriate level based on response status
        $level = $this->getLogLevel($response->getStatusCode());
        Log::channel('requests')->$level('API Request', $logData);
    }

    /**
     * Check if logging should be skipped for this request
     *
     * @param Request $request
     * @return bool
     */
    protected function shouldSkipLogging(Request $request): bool
    {
        $skipPaths = [
            '_debugbar',
            'horizon',
            'telescope',
            'health-check'
        ];

        return collect($skipPaths)->contains(function ($path) use ($request) {
            return str_starts_with($request->path(), $path);
        });
    }

    /**
     * Filter sensitive data from request
     *
     * @param array $data
     * @return array
     */
    protected function filterRequestData(array $data): array
    {
        $sensitiveFields = [
            'password',
            'password_confirmation',
            'credit_card',
            'card_number',
            'cvv',
            'secret'
        ];

        return collect($data)->map(function ($value, $key) use ($sensitiveFields) {
            if (in_array(strtolower($key), $sensitiveFields)) {
                return '******';
            }
            return $value;
        })->all();
    }

    /**
     * Filter sensitive headers
     *
     * @param array $headers
     * @return array
     */
    protected function filterHeaders(array $headers): array
    {
        $sensitiveHeaders = [
            'authorization',
            'cookie',
            'x-csrf-token'
        ];

        return collect($headers)->map(function ($value, $key) use ($sensitiveHeaders) {
            if (in_array(strtolower($key), $sensitiveHeaders)) {
                return ['******'];
            }
            return $value;
        })->all();
    }

    /**
     * Get response content
     *
     * @param Response $response
     * @return mixed
     */
    protected function getResponseContent($response)
    {
        if (method_exists($response, 'content')) {
            $content = $response->content();
            if ($this->isJsonString($content)) {
                return json_decode($content, true);
            }
            return $content;
        }
        return null;
    }

    /**
     * Check if string is valid JSON
     *
     * @param string $string
     * @return bool
     */
    protected function isJsonString($string): bool
    {
        if (!is_string($string)) {
            return false;
        }
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Get appropriate log level based on status code
     *
     * @param int $statusCode
     * @return string
     */
    protected function getLogLevel(int $statusCode): string
    {
        if ($statusCode >= 500) {
            return 'error';
        }
        if ($statusCode >= 400) {
            return 'warning';
        }
        return 'info';
    }
} 