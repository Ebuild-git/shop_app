<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserLocked
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && $user->locked) {
            return response()->json([
                'status'  => false,
                'message' => 'Your account has been locked. Please contact support.',
            ], 403);
        }

        return $next($request);
    }
}
