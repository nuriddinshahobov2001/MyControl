<?php

namespace App\Http\Services;


use App\Models\Client;
use App\Models\Credit_Debit;
use App\Models\Credit_Debit_History;
use Illuminate\Support\Facades\Auth;

class CreditDebitService {


    public function show($id) {
        return Credit_Debit::where('client_id', $id)->get();
    }

    public function store($data) {

        $client = Client::find($data['client_id']);
//        if ($data['type'] === 'debit') {
//            $client->limit -= $data['summa'];
//            $client->save();
//        } else {
//            $client->limit += $data['summa'];
//            $client->save();
//        }

        Credit_Debit_History::create([
            'date' => $data['date'],
            'client_id' => $data['client_id'],
            'author_id' => Auth::id(),
            'store_id' => $data['store_id'],
            'summa' => $data['summa'],
            'description' => $data['description'],
            'type' => $data['type']
        ]);

        return Credit_Debit::create([
           'date' => $data['date'],
           'client_id' => $data['client_id'],
           'author_id' => Auth::id(),
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
            'author_id' => Auth::id(),
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
            'author_id' => Auth::id(),
            'store_id' => $data['store_id'],
            'summa' => $data['summa'],
            'description' => $data['description'],
            'type' => $data['type']
        ]);

        $credit->type = $data['type'];
        $credit->save();
        return $credit;
    }

    public function delete($request)
    {
        $data = Credit_Debit::where('id', $request->id)->first();
        $history = Credit_Debit_History::where('id', $request->id)->first();

        if ($data != null) {
            $client = Client::find($data['client_id']);

            if ($data['type'] === 'debit') {
                $client->limit += $data['summa'];
                $client->save();
            } elseif ($data['type'] === 'credit') {
                $client->limit -= $data['summa'];
                $client->save();
            }

            $data->delete();
            $history->delete();

           return true;
        }
        return false;
    }

}

?>
