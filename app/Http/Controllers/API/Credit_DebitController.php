<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreditDebitRequest;
use App\Http\Resources\ClientResource;
use App\Http\Resources\CreditDebitResource;
use App\Http\Services\CreditDebitService;
use Carbon\Carbon;
use Carbon\Exceptions\Exception;
use Illuminate\Support\Collection;

class Credit_DebitController extends Controller
{
    private CreditDebitService $creditDebitService;

    public function __construct(CreditDebitService $creditDebitService)
    {
        $this->creditDebitService = $creditDebitService;
    }

    public function show($id) {
        $credits = $this->creditDebitService->show($id);

        return response()->json([
            'status' => true,
            'data' => CreditDebitResource::collection($credits)
        ]);
    }

    public function store(CreditDebitRequest $request) {
        $data = $request->validated();


        $credit = $this->creditDebitService->store($data);

        return response()->json([
            'status' => true,
            'credit' => new CreditDebitResource($credit)
        ]);
    }

    public function update(CreditDebitRequest $request, $id) {
        $data = $request->validated();
        $credit = $this->creditDebitService->update($data, $id);

        return response()->json([
            'status' => true,
            'credit' => new CreditDebitResource($credit)
        ]);
    }





}
