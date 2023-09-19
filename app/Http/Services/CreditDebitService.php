<?php

namespace App\Http\Services;


use App\Models\Credit_Debit;

class CreditDebitService {


    public function show($id) {
        return Credit_Debit::where('clint_id', $id)->get();
    }

    public function store($data) {
        return Credit_Debit::create([
           'date' => $data['date'],
           'client_id' => $data['client_id'],
           'author_id' => $data['author_id'],
           'store_id' => $data['store_id'],
           'summa' => $data['summa'],
           'description' => $data['description']
        ]);
    }
    public function update($data, $id) {
        $credit =  Credit_Debit::find();

       return $credit->update([
            'date' => $data['date'],
            'client_id' => $data['client_id'],
            'author_id' => $data['author_id'],
            'store_id' => $data['store_id'],
            'summa' => $data['summa'],
            'description' => $data['description']
        ]);
    }




}



?>
