<?php

namespace App\Http\Middleware;

use App\Http\Traits\ApiResponser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Administrator
{

    use ApiResponser;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // For administrator only
        if (!Auth::guard('api')->check())
            return $this->errorResponse("User unauthenticated or Invalid Token",  401);

        if (Auth::guard('api')->user()->role !== 1)
            return $this->errorResponse("You are not allowed", 401);

        return $next($request);
    }
}
