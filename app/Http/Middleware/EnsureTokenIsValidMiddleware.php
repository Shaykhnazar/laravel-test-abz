<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Carbon\Carbon;

class EnsureTokenIsValidMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Retrieve the token from the request header
        $token = $request->header('Token');

        if (!$token) {
            return response()->error('Token is required.', 401);
        }

        // Remove "Bearer " from the token if it exists
        $token = str_replace('Bearer ', '', $token);

        // Find the token in the personal_access_tokens table
        $tokenRecord = PersonalAccessToken::findToken($token);

        if (!$tokenRecord) {
            return response()->error('Invalid token.', 401);
        }

        // Check if the token has expired (assuming 'expires_at' is the column for expiration)
        if ($tokenRecord->expires_at && Carbon::now()->greaterThan($tokenRecord->expires_at)) {
            return response()->error('The token has expired.', 401);
        }

        // Allow the request to proceed if the token is valid
        return $next($request);
    }
}
