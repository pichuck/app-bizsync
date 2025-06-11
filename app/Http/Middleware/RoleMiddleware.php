<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        // Load roles relationship untuk memastikan data ter-load
        $user = auth()->user()->load('roles');
        
        foreach ($roles as $role) {
            // Gunakan relationship check yang konsisten
            if ($user->roles->contains('name', $role)) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized access');
    }
}