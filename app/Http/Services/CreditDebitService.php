<?php

namespace App\Http\Services;


use App\Models\Credit_Debit;
use App\Models\Credit_Debit_History;

class CreditDebitService {


    public function show($id) {
        return Credit_Debit::where('client_id', $id)->get();
    }

    public function store($data) {
        Credit_Debit_History::create([
            'date' => $data['date'],
            'client_id' => $data['client_id'],
            'author_id' => $data['author_id'],
            'store_id' => $data['store_id'],
            'summa' => $data['summa'],
            'description' => $data['description'],
            'type' => $data['type']
        ]);

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
        $creditHistory = Credit_Debit_History::find($id);

        $creditHistory->update([
            'date' => $data['date'],
            'client_id' => $data['client_id'],
            'author_id' => $data['author_id'],
            'store_id' => $data['store_id'],
            'summa' => $data['summa'],
            'description' => $data['description'],
            'type' => $data['type']
        ]);
        $creditHistory->type = $data['type'];
        $creditHistory->save();

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
