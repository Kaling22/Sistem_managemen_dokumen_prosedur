<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DepartmentAccess
{
    public function handle(Request $request, Closure $next, $department)
    {
        $user = auth()->user();

        // Admin selalu boleh
        if ($user->role == 0) {
            return $next($request);
        }

        // Cocokkan departemen user
        if (strtoupper($user->departemen) === strtoupper($department)) {
            return $next($request);
        }

        abort(403, 'ANDA TIDAK PUNYA AKSES');
    }
}
