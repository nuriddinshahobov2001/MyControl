<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\CalculationInterface;
use App\Http\Resources\CreditDebitResource;
use App\Models\Credit_Debit;

use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\Credit_Debit_History;

use Illuminate\Http\JsonResponse;
use \App\Models\Client;
use Illuminate\Support\Facades\Storage;
use function Pest\Laravel\json;

class CalculationController extends Controller implements CalculationInterface
{

    public function history($client_id, $from, $to): JsonResponse
    {
        $histories = Credit_Debit_History::where('client_id', $client_id)
            ->whereBetween('date', [$from, $to])
            ->get();

        $debt_credit = Credit_Debit_History::selectRaw('SUM(CASE WHEN type = "credit" THEN summa ELSE 0 END) as credit, SUM(CASE WHEN type = "debit" THEN summa ELSE 0 END) as debit')
            ->where([
                ['date', '<', $from],
                ['client_id', $client_id]
            ])->get();

        $debt_at_begin = $debt_credit[0]->debit - $debt_credit[0]->credit;

        $debit = 0;
        $credit = 0;

        foreach ($histories as $history) {
            if ($history->type === 'debit') {
                $debit += $history->summa;
            } else {
                $credit += $history->summa;
            }
        }

        $res = $debt_at_begin + ($debit - $credit);


        $randomNumber = mt_rand(1000, 9999);
        $imagePath = 'akt/' . $randomNumber . '.pdf';

        $pdf = PDF::loadView('pdf', compact('histories'));

        Storage::disk('public')->put($imagePath, $pdf->output());
        $url = Storage::url($imagePath);

        return response()->json([
            'status' => true,
            'debt_at_begin' => $debt_at_begin,
            'debt_at_finish' => $res,
            'history' => $histories,
            'url' => $url
        ]);
    }

    public function clientDebt($from, $to): JsonResponse
    {
        $debts = Credit_Debit_History::selectRaw('client_id, SUM(CASE WHEN type = "credit" THEN summa ELSE 0 END) as credit, SUM(CASE WHEN type = "debit" THEN summa ELSE 0 END) as debit')
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
                'debt' => $debt->credit - $debt->debit
            ];
        }

        return response()->json([
            'client_debts' => $clientDebts
        ]);

    }

    public function calculate(): JsonResponse
    {
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


    public function pdf()
    {
        $data = Credit_Debit::get();

        $randomNumber = mt_rand(1000, 9999);
        $imagePath = 'akt/' . $randomNumber . '.pdf';

        $pdf = PDF::loadView('pdf', compact('data'));

        Storage::disk('public')->put($imagePath, $pdf->output());
        $url = Storage::url($imagePath);



        return response()->json([
            'url' => $url
        ]);

    }
}
















