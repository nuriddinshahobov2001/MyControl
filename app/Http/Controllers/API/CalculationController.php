<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\CalculationInterface;
use App\Http\Resources\HistoryResource;
use App\Models\Credit_Debit;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Credit_Debit_History;
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

            $debt_credit = Credit_Debit_History::selectRaw('
                    SUM(CASE WHEN type = "credit" THEN summa ELSE 0 END) as credit,
                    SUM(CASE WHEN type = "debit" THEN summa ELSE 0 END) as debit')
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

            $client = Client::find($client_id);
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
//                'url' => url($url)
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
        $debts_at_begin = Credit_Debit_History::selectRaw('client_id,
                 SUM(CASE WHEN type = "credit" THEN summa ELSE 0 END) as credit,
                 SUM(CASE WHEN type = "debit" THEN summa ELSE 0 END) as debit')
            ->where('date', '<', $from)
            ->groupBy('client_id')
            ->get();

        $debts = Credit_Debit_History::selectRaw('client_id,
                 SUM(CASE WHEN type = "credit" THEN summa ELSE 0 END) as credit,
                 SUM(CASE WHEN type = "debit" THEN summa ELSE 0 END) as debit')
            ->where('date', '>=', $from)
            ->where('date', '<=', $to)
            ->groupBy('client_id')
            ->get();

        $allDebitCredit = Credit_Debit_History::selectRaw('
            SUM(CASE WHEN type = "credit" THEN summa ELSE 0 END) as credit,
            SUM(CASE WHEN type = "debit" THEN summa ELSE 0 END) as debit
        ')->where([
            ['date', '>=', $from],
            ['date', '<=', $to],
        ])->get();

        $clientDebts = [];
        $c = 0;
        foreach ($debts as $debt) {
            $fio = Client::find($debt->client_id);
            $debt_at_begin = 0;
            if (isset($debts_at_begin[$c]->client_id)) {
                if ($debts_at_begin[$c]->client_id === $debt->client_id) {
                    $debt_at_begin = $debts_at_begin[$c]->debit - $debts_at_begin[$c]->credit;
                }
            }

            $clientDebts[] = [
                'client' => $fio?->fio ?? 'Удалённый клиент',
                'debit' => $debt->debit,
                'credit' => $debt->credit,
                'debt_at_begin' => number_format($debt_at_begin, 2) ?? 0,
                'debt_at_finish' => number_format($debt_at_begin + $debt->debit - $debt->credit, 2)
            ];

            $c++;
        }
        $allDebt = $allDebitCredit[0]->debit - $allDebitCredit[0]->credit;

        return response()->json([
            'message' => true,
            'all_debit' => $allDebitCredit[0]->debit,
            'all_credit' => $allDebitCredit[0]->credit,
            'all_debt' => (string) $allDebt,
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
            ])->whereDate('date', '<=', now()->subDays($client->amount))->get();


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

}
















