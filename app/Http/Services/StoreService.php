<?php

namespace App\Http\Services;

use App\Models\Client;
use App\Models\Store;
use App\Models\User;

class StoreService {

    public function index() {
        return Store::get();
    }

    public function store($data) {
       return Store::create([
           'name' => $data['name'],
           'address' => $data['address'],
           'description' => $data['description'],
       ]);
    }

    public function update($id, $data) {
        $user = Store::find($id);

        
        $user->update([
            'name' => $data['name'],
            'address' => $data['address'],
            'description' => $data['description'],
        ]);

        return $user;
    }
    public function delete($id) {
        return Store::find($id)?->delete();
    }

    public function show($id) {
        return Store::find($id);
    }



}







?>
