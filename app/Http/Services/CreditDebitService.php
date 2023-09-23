<?php

namespace App\Http\Services;


use App\Models\Credit_Debit;

class CreditDebitService {


    public function show($id) {
        return Credit_Debit::where('client_id', $id)->get();
    }

    public function store($data) {
        return Credit_Debit::create([
           'date' => $data['date'],
           'client_id' => $data['client_id'],
           'author_id' => $data['author_id'],
           'store_id' => $data['store_id'],
           'summa' => $data['summa'],
           'description' => $data['description'],
           'type' => $data['type']
        ]);
    }
    public function update($data, $id) {
        $credit =  Credit_Debit::find($id);


        $credit->update([
            'date' => $data['date'],
            'client_id' => $data['client_id'],
            'author_id' => $data['author_id'],
            'store_id' => $data['store_id'],
            'summa' => $data['summa'],
            'description' => $data['description'],
            'type' => $data['type']
        ]);
        $credit->type = $data['type'];
        $credit->save();
        return $credit;
    }

}



?>
