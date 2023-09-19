<?php

namespace App\Http\Services;


use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService {

    public function index() {
        return User::role('user')->get();
    }

    public function store($data) {
        return User::create([
            'name' => $data['name'],
            'lastname' => $data['lastname'],
            'login' => $data['login'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'img' => Hash::make($data['img'])
        ])->assingRole('user');
    }


}




?>
