<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserRoles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        $userRoles = Auth::user()->roles->pluck('name');

        $userRoles->contains(function ($value, $key) use ($roles) {
            if (!in_array($value, $roles)) {
                return abort(403, 'Unauthorized action.');
            }
        });

        return $next($request);
    }
}
