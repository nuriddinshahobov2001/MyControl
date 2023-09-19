<?php

namespace App\Http\Services;

use App\Models\User;

class ClientService {

    public function index() {
        return User::role('client')->get();
    }

    public function store($data) {
       return User::create([
           'name' => $data['name'],
           'lastname' => $data['lastname'],
           'address' => $data['address'],
           'phone' => $data['phone'],
           'description' => $data['description'],
           'login' => $data['login'],
           'password' => $data['password'],
           'img' => $data['img']
       ])->assignRole('client');
    }

    public function update($id, $data) {
        $user = User::find($id);
        $user->update([
            'name' => $data['name'],
            'lastname' => $data['lastname'],
            'address' => $data['address'],
            'phone' => $data['phone'],
            'description' => $data['description'],
            'login' => $data['login'],
            'password' => $data['password'],
            'img' => $data['img']
        ]);

        return $user;
    }
    public function delete($id) {
        return User::find($id)->delete();
    }



}







?>
