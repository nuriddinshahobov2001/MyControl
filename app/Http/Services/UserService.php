<?php

namespace App\Http\Services;


use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService {

    public function store($data) {
        return User::create([
            'fio' => $data['fio'],
            'login' => $data['login'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function update($user, $data)
    {
        try {
            if (isset($data['login']) && $data['login'] !== 'admin'){
                $user->update([
                    'fio' => $data['fio'],
                    'login' => $data['login'],
                    'phone' => $data['phone'],
                    'password' => Hash::make($data['password'])
                ]);
            } else {
                $user->update([
                    'fio' => $data['fio'],
                    'phone' => $data['phone'],
                    'password' => Hash::make($data['password'])
                ]);
            }

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


}




?>
