<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        Log::info('RoleMiddleware dijalankan', ['role' => $role]);

        if (Auth::check() && Auth::user()->role === $role) {
            return $next($request);
        }

        Log::warning('Akses ditolak. Role tidak sesuai.', ['user_id' => Auth::id(), 'required_role' => $role]);

        abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
    }
}
