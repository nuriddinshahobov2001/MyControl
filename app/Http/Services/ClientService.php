<?php

namespace App\Http\Services;

use App\Models\Client;
use App\Models\User;

class ClientService {

    public function index() {
        return User::role('client')->get();
    }

    public function store($data) {
       return Client::create([
           'fio' => $data['fio'],
           'address' => $data['address'],
           'phone' => $data['phone'],
           'description' => $data['description'],
           'limit' => $data['limit'],
           'amount' => $data['amount']
       ]);
    }

    public function update($id, $data) {
        $user = Client::find($id);
        $user->update([
            'fio' => $data['fio'],
            'address' => $data['address'],
            'phone' => $data['phone'],
            'description' => $data['description'],
            'limit' => $data['limit'],
            'amount' => $data['amount']
        ]);

        return $user;
    }
    public function delete($id) {
        return Client::find($id)?->delete();
    }

    public function show($id) {
        return Client::find($id);
    }



}







?>
