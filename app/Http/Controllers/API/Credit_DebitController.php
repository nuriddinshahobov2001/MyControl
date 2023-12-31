<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Http\Requests\CreditDebitRequest;
use App\Http\Resources\CreditDebitResource;
use App\Http\Services\CreditDebitService;
use App\Models\Client;
use App\Models\Credit_Debit;
use App\Models\Credit_Debit_History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Credit_DebitController extends Controller
{
    private CreditDebitService $creditDebitService;

    public function __construct(CreditDebitService $creditDebitService)
    {
        $this->creditDebitService = $creditDebitService;
    }

    public function show($id)
    {
        $credits = $this->creditDebitService->show($id);

        return response()->json([
            'status' => true,
            'data' => CreditDebitResource::collection($credits)
        ]);
    }

    public function store(Request $request)
    {
        $data = Validator::make($request->all(), [
            'date' => 'required|date_format:Y-m-d',
            'client_id' => 'required|integer',
            'summa' => 'required|numeric',
            'description' => 'nullable',
            'type' => 'in:credit,debit'
        ], [
            'date.required' => 'Поле дата объязательно для заполнения.',
            'date.date' => 'Значение поля дата не является датой.',
            'client_id.required' => 'Поле клиент объязательно для заполнения.',
            'client_id.integer' => 'Значение поле клиент должно быть целым числом.',
            'summa.required' => 'Поле сумма объязательно для заполнения.',
            'summa.numeric' => 'Значение поле сумма должно быть числом.'
        ]);

        if ($data->fails()) {
            return response()->json([
                'message' => false,
                'errors' => $data->errors()
            ], 200);
        }

        $data = $data->validated();

        $credit = $this->creditDebitService->store($data);
        $client = Client::find($credit->client_id);
        if (!$client) {
            return response()->json([
                'message' => 'Такой клиент не существует'
            ]);
        }

        if ($credit->type === 'credit') {
            $debts = Credit_Debit::where([
                ['hasRecorded', 0],
                ['type', 'debit'],
                ['client_id', $credit->client_id]
            ])->get();

            if ($credit->summa > 0 && count($debts) == 0) {
                $client->balance += $credit->summa;
                $client->save();
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
                        $client->balance +=$credit->summa;
                        $client->save();
                    }
                }
            }
        } else {
            if ($client) {
                if ($client->balance >= 0) {
                    if ($client->balance >= $credit->summa) {
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
            'credit' => CreditDebitResource::make($credit)
        ]);

    }

    public function edit(Request $request)
    {
        $data = Credit_Debit_History::where('id', $request->id)->first();
        return response()->json([
            'status' => true,
            'data' => [
                'date' => $data?->date,
                'client_id' => $data?->client_id,
                'client_name' => $data?->client?->fio,
                'summa' => $data?->summa,
                'description' => $data?->description
            ],
        ]);
    }

    public function update(CreditDebitRequest $request, $id)
    {
        $data = $request->validated();
        $credit = $this->creditDebitService->update($data, $id);

        return response()->json([
            'status' => true,
            'credit' => new CreditDebitResource($credit)
        ]);
    }

    public function delete(Request $request)
    {
        $res = $this->creditDebitService->delete($request);

        if ($res) {
            return response()->json([
                'message' => true,
                'info' => "Успешно удалено!"
            ]);
        }
        return response() ->json([
            'message' => false,
            'info' => 'Уже удалено!'
        ]);
    }

}
