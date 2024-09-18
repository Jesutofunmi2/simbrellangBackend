<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{   

    /**
     * Create a new user with the provided data.
     *
     * @param array $data Array containing user data including name, email, and password.
     * 
     * @return User The newly created user instance.
     * 
     */
    public function createUser(array $data): User
    {
        $user = new User;

        DB::beginTransaction();

            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = Hash::make($data['password']);
            $user->save();

        DB::commit();

        return $user;
    }
}
