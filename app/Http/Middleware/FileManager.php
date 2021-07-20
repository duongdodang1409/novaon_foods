<?php

namespace App\Http\Middleware;

use App\ApiCode;
use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;


class FileManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        try {
            JWTAuth::parseToken()->toUser();
            return $next($request);
        } catch (\Exception $e) {
            abort(ApiCode::UN_AUTHENTICATED);
        }
        abort(ApiCode::UN_AUTHENTICATED);
        return $next($request);
    }
}
