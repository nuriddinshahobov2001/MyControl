<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CheckResource;
use App\Models\Credit_Debit_History;
use Illuminate\Http\Request;

class CheckController extends Controller
{
    public function check()
    {
        $checks = Credit_Debit_History::orderByDesc('date')->get();

        return response()->json([
            'message' => true,
            'checks' => CheckResource::collection($checks)
        ]);
    }
}
