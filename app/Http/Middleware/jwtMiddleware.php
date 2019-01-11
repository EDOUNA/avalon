<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use JWTA;

class jwtMiddleware
{
    /**
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function handle($request, Closure $next)
    {
        $user = null;
        try {
            $user = JWTAuth::toUser($request->input('token'));
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return $next($request);
                return response()->json(['error' => 'Token is Invalid']);
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return $next($request);
                return response()->json(['error' => 'Token is Expired']);
            } else {
                return $next($request);
                return response()->json(['error' => 'Something is wrong']);
            }
        }
        return $next($request);
    }
}
