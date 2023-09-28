<?php

namespace App\Http\Services;

use App\Models\Client;
use App\Models\Credit_Debit_History;
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

    public function getClientInfo($id) {
        $client = Client::find($id);

        $debit_credit = Credit_Debit_History::selectRaw(
            'SUM(CASE WHEN type = "credit" THEN summa ELSE 0 END) as credit,
             SUM(CASE WHEN type = "debit" THEN summa ELSE 0 END) as debit')
            ->where('client_id', $id)->get();

        $debit = $debit_credit[0]->debit;
        $credit = $debit_credit[0]->credit;
        $debt = $debit - $credit;

        $history_of_debit = Credit_Debit_History::where([
            ['client_id', $id],
            ['type', 'debit']
        ])->get();

        $history_of_credit = Credit_Debit_History::where([
            ['client_id', $id],
            ['type', 'credit']
        ])->get();

        return [
            'fio' => $client->fio,
            'limit' => $client->limit,
            'debt' => $debt,
            'all_debit' => $debit,
            'all_credit' => $credit,
            'history_of_debit' => $history_of_debit,
            'history_of_credit' => $history_of_credit
        ];

    }


}







?>
