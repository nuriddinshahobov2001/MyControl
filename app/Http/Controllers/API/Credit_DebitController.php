<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreditDebitRequest;
use App\Http\Resources\ClientResource;
use App\Http\Resources\CreditDebitResource;
use App\Http\Services\CreditDebitService;
use App\Models\Client;
use App\Models\Credit_Debit;
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

    public function store(CreditDebitRequest $request)
    {
        $data = $request->validated();

        $credit = $this->creditDebitService->store($data);
        if ($credit->type === 'credit') {
            $debts = Credit_Debit::where([
                ['hasRecorded', 0],
                ['type', 'debit'],
                ['client_id', $credit->client_id]
            ])->get();

            if ($credit->summa > 0 && count($debts) == 0) {

                $user = Client::find($credit->client_id);
                $user->balance += $credit->summa;
                $user->save();
            }



            $lastKey = count($debts) - 1;

            foreach ($debts as $key => $debt) {

                if ($debt && $credit->summa >= $debt->summa) {
                    $credit->summa -= $debt->summa;
                    $debt->hasRecorded = true;
                    $debt->save();


                } elseif ($credit->summa < $debt->summa) {
                    $debt->summa -= $credit->summa;
                    $debt->save();

                    $credit->summa = 0;

                }

                if ($key === $lastKey) {
                    if ($credit->summa > 0) {

                        $user = Client::find($credit->client_id);
                        $user->balance += $credit->summa;
                        $user->save();
                    }
                }
            }
        }
        else {
            $client = Client::find($credit->client_id);
            if ($client) {
                if ($client->balance > 0) {
                    if ($client->balance > $credit->summa){
                        $client->balance -= $credit->summa;
                        $client->save();

                        $credit->hasRecorded = true;
                        $credit->save();
                    } else {
                        $credit->summa -= $client->balance;
                        $credit->save();

                        $client->balance = 0;
                        $client->save();
                    }
                }
            }
        }

        return response()->json([
            'status' => true,
            'credit' => $credit
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
