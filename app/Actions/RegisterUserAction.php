<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterUserAction
{
    /**
     * Handle the registration and user creation logic.
     *
     * @param array $data
     * @return \App\Models\User
     */
    public function execute(array $data): User
    {
        $data['password'] = Hash::make($data['password']);

        return User::create($data);
    }
}
