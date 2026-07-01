<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class EnhancedApiRateLimit
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $limit = '100', int $decayMinutes = 1): Response
    {
        $key = $this->throttleKey($request);
        
        if (RateLimiter::tooManyAttempts($key, $limit)) {
            $retryAfter = RateLimiter::availableIn($key);
            
            return response()->json([
                'message' => 'Too many requests',
                'error_code' => 'RATE_LIMIT_EXCEEDED',
                'retry_after' => $retryAfter
            ], 429, [
                'X-RateLimit-Limit' => $limit,
                'X-RateLimit-Remaining' => RateLimiter::remaining($key, $limit),
                'X-RateLimit-Reset' => now()->addSeconds($retryAfter)->timestamp,
                'Retry-After' => $retryAfter
            ]);
        }

        $response = $next($request);

        // Add rate limiting headers to successful responses
        $remaining = RateLimiter::remaining($key, $limit);
        
        $response->headers->add([
            'X-RateLimit-Limit' => $limit,
            'X-RateLimit-Remaining' => max(0, $remaining),
            'X-RateLimit-Reset' => RateLimiter::availableIn($key) + now()->timestamp
        ]);

        // Increment attempts
        RateLimiter::hit($key, $decayMinutes * 60);

        return $response;
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    protected function throttleKey(Request $request): string
    {
        $user = $request->user();
        $key = $user ? "api_rate_limit:user_{$user->id}" : "api_rate_limit:ip_{$request->ip()}";
        
        // Add endpoint-specific limiting
        $endpoint = $request->route()?->getName() ?? $request->path();
        $key .= "_" . md5($endpoint);
        
        return $key;
    }
}