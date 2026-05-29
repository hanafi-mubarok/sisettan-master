<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        if ($user->hasAnyRole(['super-admin', 'admin'])) {
            return $next($request);
        }

        if (
            !$user->isverified
            && $request->routeIs('dashboard.penawaran.store')
            && !$request->routeIs('approval.pending')
        ) {
            return redirect()->route('approval.pending');
        }

        return $next($request);
    }
}
