<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class BlockIpAfterLoginAttempts
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        $threshold = config('login.throttle_threshold', 4);
        $duration = config('login.block_duration', 5);
    
        $key = 'login_attempts_' . $ip;
    
        $loginAttempts = Cache::get($key, 0) + 1;
        Cache::put($key, $loginAttempts, now()->addMinutes(1));
    
        if ($loginAttempts > $threshold) {
            Cache::put($key, 0, now()->addMinutes($duration));
    
            abort(403, 'Too many attempt. Please try again after 5 minutes.');
        }
    
        return $next($request);
    }
    
}
