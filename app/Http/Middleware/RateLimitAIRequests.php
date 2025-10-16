<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RateLimitAIRequests
{
    protected $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    public function handle(Request $request, Closure $next)
    {
        $key = 'ai_requests:' . $request->user()->id;

        if ($this->limiter->tooManyAttempts($key, config('ai.rate_limits.per_minute'))) {
            return response()->json([
                'error' => 'Too many requests',
                'retry_after' => $this->limiter->availableIn($key)
            ], Response::HTTP_TOO_MANY_REQUESTS);
        }

        $this->limiter->hit($key);

        return $next($request);
    }
}
