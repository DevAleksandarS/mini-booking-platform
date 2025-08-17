<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = auth()->user();

        if ($user->role === $role) {
            return $next($request);
        }

        if ($user->role === 'admin') {
            $path = '/admin' . $request->getPathInfo();
            return redirect($path);
        }

        abort(403, 'Unauthorized');
    }
}
