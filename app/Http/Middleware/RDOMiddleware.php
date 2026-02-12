<?php

namespace App\Http\Middleware;

use App\Enums\InstructorRole;
use App\Models\Teacher;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RDOMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check() || auth()->user()->profileable_type !== Teacher::class || auth()->user()->profile->role !== InstructorRole::RDO) {
            abort(403, 'Unauthorized access. RDO role required.');
        }

        return $next($request);
    }
}
