<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\CalculationInterface;
use App\Http\Resources\CreditDebitResource;
use App\Models\Credit_Debit;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use \App\Models\Client;

class CalculationController extends Controller implements CalculationInterface
{

    public function history($client_id, $from, $to): JsonResponse
    {
        $histories = Credit_Debit::where('client_id', $client_id)
            ->whereBetween('date', [$from, $to])
            ->get();

        return response()->json([
            'status' => true,
            'history' => CreditDebitResource::collection($histories),
        ]);
    }

    public function clientDebt($from, $to): JsonResponse
    {
        $debts = Credit_Debit::selectRaw('client_id, SUM(CASE WHEN type = "credit" THEN summa ELSE 0 END) as credit, SUM(CASE WHEN type = "debit" THEN summa ELSE 0 END) as debit')
            ->where('date', '>=', $from)
            ->where('date', '<=', $to)
            ->groupBy('client_id')
            ->get();

        $clientDebts = [];

        foreach ($debts as $debt) {
            $fio = Client::find($debt->client_id);

            $clientDebts[] = [
                'client' => $fio->fio,
                'debit' => $debt->debit,
                'credit' => $debt->credit,
                'debt' => $debt->debit - $debt->credit
            ];
        }

        return response()->json([
            'client_debts' => $clientDebts
        ]);

    }

    public function calculate() {
        $clients = Client::get();
        $array_of_dates = [];

        foreach($clients as $client) {
            $history = Credit_Debit::where('client_id', $client->id)->orderBy('date')->get();
            $balance = 0;
            $debtAlreadyRecorded = false;

            foreach($history as $h) {
                if ($h->type === 'credit') {
                    $balance += $h->summa;
                } elseif ($h->type === 'debit') {
                    $balance -= $h->summa;
                    if ($balance < 0 && !$debtAlreadyRecorded) {
                        $debtPaid = Credit_Debit::where('client_id', $client->id)
                            ->where('date', '<=', $h->date)
                            ->where('type', 'credit')
                            ->sum('summa');

                        if ($debtPaid < -$balance) {
                            $array_of_dates[] = [
                                'client_id' => $client->id,
                                'date' => $h->date,
                                'debt_amount' => -$balance,
                            ];

                            $debtAlreadyRecorded = true;
                        }
                    }
                }
            }
        }

        return response()->json($array_of_dates);
    }





}
