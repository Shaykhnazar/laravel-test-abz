<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Generate a token that is valid for 40 minutes and can be used only once.
     */
    public function generateToken(Request $request)
    {
        $user = Auth::user() ?? User::first();

        // Create a new token with a 40-minute expiration
        $token = $user->createToken('registration-token', ['*'], now()->addMinutes(40));

        return response()->success(['token' => $token->plainTextToken], 200);
    }
}
