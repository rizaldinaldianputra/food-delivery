<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Authenticate
{
    public function handle($request, Closure $next)
    {
        try {
            // Cek apakah token valid
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return response()->json(['error' => 'Unauthorized', 'message' => 'User tidak ditemukan'], 401);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Unauthorized', 'message' => 'Token expired'], 401);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Unauthorized', 'message' => 'Token tidak valid'], 401);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Unauthorized', 'message' => 'Token tidak ada'], 401);
        }

        return $next($request);
    }
}