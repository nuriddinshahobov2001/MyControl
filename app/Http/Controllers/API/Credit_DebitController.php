<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreditDebitRequest;
use App\Http\Resources\ClientResource;
use App\Http\Services\CreditDebitService;

class Credit_DebitController extends Controller
{
    private CreditDebitService $creditDebitService;

    public function __construct(CreditDebitService $creditDebitService)
    {
        $this->creditDebitService = $creditDebitService;
    }

    public function show($id) {
        $credit = $this->creditDebitService->show($id);

        return response()->json([
            'status' => true,
            'data' => new CreditDebitRequest($credit)
        ]);
    }

    public function store(CreditDebitRequest $request) {
        $data = $request->validated();
        $this->creditDebitService->store($data);


        return response()->json([
            'status' => true,
        ]);
    }

    public function update(CreditDebitRequest $request, $id) {
        $data = $request->validated();
        $client = $this->creditDebitService->update($id, $data);

        return response()->json([
            'status' => true,
            'credit' => new ClientResource($client)
        ]);
    }


}
