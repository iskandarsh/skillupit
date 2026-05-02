<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckLevel
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle($request, Closure $next, ...$levels)
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        // cek level user
        if (!in_array(auth()->user()->level, $levels)) {
            abort(403, 'Akses ditolak');
        }

        return $next($request);
    }
}
