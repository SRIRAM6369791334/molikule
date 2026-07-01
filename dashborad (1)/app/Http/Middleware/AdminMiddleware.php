<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->user()->isAdmin()) {
            \Log::warning('AdminMiddleware: Access Denied', [
                'user_id' => auth()->id(),
                'user_type' => auth()->user()->user_type,
                'path' => $request->path()
            ]);
            abort(403, 'Access denied. Admin privileges required.');
        }

        \Log::info('AdminMiddleware: Access Granted', [
            'user_id' => auth()->id(),
            'path' => $request->path()
        ]);
        return $next($request);
    }
}
