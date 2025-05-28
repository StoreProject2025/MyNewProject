<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->header('X-API-Key');
        
        if (!$apiKey) {
            return response()->json([
                'message' => 'API key is missing'
            ], Response::HTTP_UNAUTHORIZED);
        }

        if (!$this->isValidApiKey($apiKey)) {
            return response()->json([
                'message' => 'Invalid API key'
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }

    /**
     * Validate API key
     *
     * @param string $apiKey
     * @return bool
     */
    protected function isValidApiKey(string $apiKey): bool
    {
        // This is just a placeholder. In a real application,
        // you would validate against a database or configuration
        $validApiKeys = [
            config('services.api.key')
        ];

        return in_array($apiKey, $validApiKeys);
    }

    /**
     * Log API key usage
     *
     * @param string $apiKey
     * @param string $endpoint
     * @return void
     */
    protected function logApiKeyUsage(string $apiKey, string $endpoint): void
    {
        // Log API key usage for monitoring
        \Log::info('API Key Usage', [
            'api_key' => substr($apiKey, 0, 8) . '...',
            'endpoint' => $endpoint,
            'timestamp' => now(),
            'ip' => request()->ip()
        ]);
    }
} 