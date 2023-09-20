<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\CalculationInterface;
use App\Http\Resources\CreditDebitResource;
use App\Models\Credit_Debit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use PHPUnit\Util\Json;

class CalculationController extends Controller implements CalculationInterface
{

    public function history($client_id, $from, $to): JsonResponse
    {
        $histories = Credit_Debit::where('client_id', $client_id)
            ->whereBetween('created_at', [$from, $to])
            ->get();

        return response()->json([
            'status' => true,
            'history' => CreditDebitResource::collection($histories)
        ]);
    }

}
