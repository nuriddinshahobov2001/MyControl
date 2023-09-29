<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\CalculationInterface;
use App\Http\Resources\CreditDebitResource;
use App\Http\Resources\HistoryResource;
use App\Models\Credit_Debit;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\Credit_Debit_History;

use http\Env\Response;
use Illuminate\Http\JsonResponse;
use \App\Models\Client;
use Illuminate\Support\Facades\Storage;


class CalculationController extends Controller implements CalculationInterface
{

    public function aktSverki($client_id, $from, $to): JsonResponse
    {
        $debit = 0;
        $credit = 0;

        if ($client_id !== "0") {
            $histories = Credit_Debit_History::where('client_id', $client_id)
                ->whereBetween('date', [$from, $to])
                ->get();

            $debt_credit = Credit_Debit_History::selectRaw('SUM(CASE WHEN type = "credit" THEN summa ELSE 0 END) as credit, SUM(CASE WHEN type = "debit" THEN summa ELSE 0 END) as debit')
                ->where([
                    ['date', '<', $from],
                    ['client_id', $client_id]
                ])->get();

            $debt_at_begin = $debt_credit[0]->debit - $debt_credit[0]->credit;


            foreach ($histories as $history) {
                if ($history->type === 'debit') {
                    $debit += $history->summa;
                } else {
                    $credit += $history->summa;
                }
            }

            $res = $debt_at_begin + ($debit - $credit);

            $client = Client::find($client_id)->first();
            $randomNumber = mt_rand(1000, 9999);
            $imagePath = 'akt/' . $randomNumber . '.pdf';

            $pdf = PDF::loadView('pdf', compact('histories', 'from', 'to', 'client', 'debt_at_begin', 'res'));
            $pdf->getDomPDF()->getOptions()->set('isHtml5ParserEnabled', true);
            $pdf->getDomPDF()->getOptions()->set('isPhpEnabled', true);
            $pdf->getDomPDF()->getOptions()->set('isPhpEnabled', true);
            $pdf->getDomPDF()->getOptions()->set('defaultFont', 'DejaVu Sans');
            Storage::disk('public')->put($imagePath, $pdf->output());
            $url = Storage::url($imagePath);

            return response()->json([
                'status' => true,
                'debt_at_begin' => number_format($debt_at_begin,  2),
                'debt_at_finish' => number_format($res, 2),
                'history' => HistoryResource::collection($histories),
                'url' => url($url)
            ]);
        } else {
            return response()->json([
                'status' => true,
                'debt_at_begin' => "0",
                'debt_at_finish' => "0",
                'history' => [],
                'url' => null
            ]);
        }

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
                'debt' => $debt->debit - $debt->credit
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
            ])->whereDate('date', '<=', now()->subDays($client->limit))->get();


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

    public function pdf() : JsonResponse
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


    public function storeHistory($id): JsonResponse
    {
       $history =  Credit_Debit_History::where('store_id', $id)->get();

        return response()->json([
            'history' => $history
        ]);
    }
}
















