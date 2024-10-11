<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
            'password'   => Hash::make($data['password']),
            'photo'      => $photoPath,
        ]);
    }
}
