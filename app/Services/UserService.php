<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class UserService
{
    /**
     * Create a new user.
     *
     * @param array $data
     * @param string|null $photoPath
     * @return User
     */
    public function createUser(array $data, ?string $photoPath): User
    {
        return User::create([
            'name'       => $data['name'],
            'email'      => $data['email'],
            'phone'      => $data['phone'],
            'position_id'=> $data['position_id'],
            'password'   => Hash::make('secret'),
            'photo'      => $photoPath,
        ]);
    }

    public function removeToken($request): void
    {
        // Delete the token after user registration
        $token = $request->bearerToken(); // Retrieve the token from Authorization header
        if ($token) {
            $tokenRecord = PersonalAccessToken::findToken($token);
            $tokenRecord?->delete();
        }
    }
}
