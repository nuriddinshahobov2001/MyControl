<?php

namespace App\Http\Services;

use App\Models\Client;
use App\Models\Credit_Debit_History;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ClientService {

    public function index() {
        return User::role('client')->get();
    }

    public function store($data) {
       return Client::create([
           'fio' => $data['fio'],
           'address' => $data['address'],
           'phone' => $data['phone'],
           'description' => $data['description'] ?? '',
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

    public function delete($id)
    {
        $credit = Credit_Debit_History::where('client_id', $id)->first();

        if ($credit === null) {
            return Client::find($id)?->delete();
        }

        return false;

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


        return [
            'fio' => $client->fio,
            'phone' => $client->phone,
            'address' => $client->address,
            'description' => $client->description,
            'limit' => $client->limit,
            'debt' => $debt,
            'all_debit' => $debit,
            'all_credit' => $credit,
            'amount' => $client->amount
        ];

    }

    public function getFiveClients()
    {
        return DB::table('clients')
            ->select('clients.id', 'clients.fio', 'clients.limit', 'clients.amount', 'clients.address',
                'clients.description', 'clients.phone', 'clients.balance',
                DB::raw('SUM(CASE WHEN credit__debit__histories.type = "debit" THEN credit__debit__histories.summa ELSE 0 END) -
                       SUM(CASE WHEN credit__debit__histories.type = "credit" THEN credit__debit__histories.summa ELSE 0 END) as debt'))
            ->leftJoin('credit__debit__histories', 'clients.id', '=', 'credit__debit__histories.client_id')
            ->groupBy('clients.id', 'clients.fio', 'clients.limit', 'clients.amount', 'clients.address',
                'clients.description', 'clients.phone', 'clients.balance')
            ->limit(5)
            ->get();
    }

    public function allDebitCreditOfClient($id)
    {
        return Credit_Debit_History::selectRaw('
                 SUM(CASE WHEN type = "credit" THEN summa ELSE 0 END) as credit,
                 SUM(CASE WHEN type = "debit" THEN summa ELSE 0 END) as debit')
            ->where('client_id', $id)
            ->get();
    }

    public function todayHistory($id)
    {
        return Credit_Debit_History::where([
            ['client_id', $id],
            ['date', today()]
        ])->orderByDesc('created_at')->get();
    }

}







?>
