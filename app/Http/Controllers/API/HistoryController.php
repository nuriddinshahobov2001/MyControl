<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\HistoryResource;
use App\Models\Credit_Debit_History;

class HistoryController extends Controller
{
    public function history($client_id, $from, $to)
    {
        if ($client_id === "0") {
            $histories = Credit_Debit_History::where('date', today())->get();

            return response()->json([
                'message' => true,
                'history' => HistoryResource::collection($histories)
            ]);
        }

        $histories = Credit_Debit_History::where('client_id', $client_id)
            ->whereBetween('date', [$from, $to])
            ->get();

        return response()->json([
           'message' => true,
           'history' => HistoryResource::collection($histories)
        ]);
    }
}
