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
        $debts = Credit_Debit::select('client_id', DB::raw('SUM(summa) as total_debt'))
            ->where('type', 'debit')
            ->whereBetween('date', [$from, $to])
            ->groupBy('client_id')
            ->get();


        return response()->json($debts);
    }
    public function calculate(): JsonResponse {
        $clients = Client::get();

        $array_of_dates = [];

        foreach ($clients as $client) {
            $history = Credit_Debit::where([
                ['client_id', $client->id],
                ['type', 'debit'],
                ['hasRecorded', false]
            ])->whereDate('date', '<=', now()->subDays(15))->get();


            foreach ($history as $h) {
                $array_of_dates[] = [
                    'client_id' => $client->id,
                    'date' => $h->date,
                    'debt_amount' => $h->summa,
                ];
            }
        }


        return response()->json($array_of_dates);
    }



















}
