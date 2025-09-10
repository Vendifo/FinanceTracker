<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * $roles — строка через запятую, например "admin,manager"
     */
    public function handle(Request $request, Closure $next, string $roles)
    {
        $roles = explode(',', $roles);

        $user = Auth::user();

        if (!$user || !in_array($user->role?->name, $roles)) {
            abort(403, 'У вас нет доступа');
        }

        return $next($request);
    }
}
