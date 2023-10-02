<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService {
    public function login($data) {
        $user = User::where('login', $data['login'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return false;
        }

        return  $user->createToken('android-token')->plainTextToken;
    }





}



?>
