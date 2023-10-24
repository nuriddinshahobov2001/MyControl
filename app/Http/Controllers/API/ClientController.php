<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Http\Resources\ClientHistoryResource;
use App\Http\Resources\ClientResource;
use App\Http\Resources\GetClientInfoResource;
use App\Http\Services\ClientService;
use App\Models\Client;
use App\Models\Credit_Debit_History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    private ClientService $clientService;
    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    public function index() {

        $clients = DB::table('clients')
            ->select('clients.id', 'clients.fio', 'clients.limit', 'clients.amount', 'clients.address',
                'clients.description', 'clients.phone', 'clients.balance',
                DB::raw('SUM(CASE WHEN credit__debit__histories.type = "debit" THEN credit__debit__histories.summa ELSE 0 END) -
                       SUM(CASE WHEN credit__debit__histories.type = "credit" THEN credit__debit__histories.summa ELSE 0 END) as debt'))
            ->leftJoin('credit__debit__histories', 'clients.id', '=', 'credit__debit__histories.client_id')
            ->groupBy('clients.id', 'clients.fio', 'clients.limit', 'clients.amount', 'clients.address',
                'clients.description', 'clients.phone', 'clients.balance')
            ->get();

        return response([
            'message' => true,
            'clients' => ClientResource::collection($clients)
        ]);
    }

    public function show($id) {
        $client = $this->clientService->show($id);

        return response()->json([
            'client' => new ClientResource($client)
        ]);
    }


    public function store(Request $request) {

        $data = Validator::make($request->all(), [
            'fio' => 'required',
            'address' => 'required',
            'phone' => 'required|unique:clients,phone,',
            'limit' => 'required|integer',
            'amount' => 'required',
            'description' => 'nullable',
        ], [
            'fio.required' => 'Поле ФИО объязательно для заполнения',
            'address.required' => 'Поле адрес объязательно для заполнения',
            'phone.required' => 'Поле телефон объязательно для заполнения',
            'phone.unique' => 'Такое значение поля телефон уже существует.',
            'limit.required' => 'Поле лимит объязательно для заполнения',
            'limit.integer' => 'Значение лимит должно быть целым числом.',
            'amount.required' => 'Поле amount объязательно для заполнения',
        ]);

        if ($data->fails()) {
            return response()->json([
                'message' => false,
                'errors' => $data->errors()
            ], 200);
        }

        $client = $this->clientService->store($data->validated());

        return response()->json([
            'status' => true,
            'client' => new ClientResource($client)
        ]);
    }


    public function getClientInfo($id) {

        $client = $this->clientService->getClientInfo($id);

        return response()->json([
           'fio' => $client['fio'],
           'phone' => $client['phone'],
           'address' => $client['address'],
           'description' => $client['description'],
           'limit' => (string)$client['limit'],
           'amount' => (string)$client['amount'],
           'debt' => number_format($client['debt'], 2),
           'all_debit' => number_format($client['all_debit'], 2),
           'all_credit' => number_format($client['all_credit'], 2),
        ]);
    }


    public function update(ClientRequest $request, $id) {
        $data = $request->validated();
        $client = $this->clientService->update($id, $data);

        return response()->json([
            'status' => true,
            'client' => new ClientResource($client)
        ]);
    }

    public function destroy($id) {
        $message = $this->clientService->delete($id);

        return response()->json([
            'status' => $message ?? false,
        ]);
    }

    public function getFiveClients()
    {
        $clients = DB::table('clients')
            ->select('clients.id', 'clients.fio', 'clients.limit', 'clients.amount', 'clients.address',
                'clients.description', 'clients.phone', 'clients.balance',
                DB::raw('SUM(CASE WHEN credit__debit__histories.type = "debit" THEN credit__debit__histories.summa ELSE 0 END) -
                       SUM(CASE WHEN credit__debit__histories.type = "credit" THEN credit__debit__histories.summa ELSE 0 END) as debt'))
            ->leftJoin('credit__debit__histories', 'clients.id', '=', 'credit__debit__histories.client_id')
            ->groupBy('clients.id', 'clients.fio', 'clients.limit', 'clients.amount', 'clients.address',
                'clients.description', 'clients.phone', 'clients.balance')
            ->limit(5)
            ->get();

        return response()->json([
            'message' => true,
            'clients' => ClientResource::collection($clients)
        ]);
    }

    public function searchClient($client)
    {
        $clients = Client::where('fio', 'like', '%' . $client . '%')->get();

        return response()->json([
            'message' => true,
            'clients' => ClientResource::collection($clients)
        ]);
    }

    public function clientHistory($id)
    {
        $history = Credit_Debit_History::where('client_id', $id)->orderByDesc('created_at')->get();

        if ($history) {
            return response()->json([
                'message' => true,
                'history' => ClientHistoryResource::collection($history),
            ]);
        } else {
            return response()->json([
                'message' => false,
                'info' => 'Такого клиента не существует!',
            ]);
        }
    }

    public function allDebitCreditOfClient($id)
    {
        $data = Credit_Debit_History::selectRaw('
                 SUM(CASE WHEN type = "credit" THEN summa ELSE 0 END) as credit,
                 SUM(CASE WHEN type = "debit" THEN summa ELSE 0 END) as debit')
            ->where('client_id', $id)
            ->get();

        $client = Client::find($id);
        $debt = $data[0]->debit - $data[0]->credit;

        return response()->json([
           'message' => true,
           'all_debit' =>  $data[0]->debit,
           'all_credit' => $data[0]->credit,
           'debt' => $debt,
           'limit' => $client->limit,
        ]);
    }

    public function todayHistory($id)
    {
        $history = Credit_Debit_History::where([
            ['client_id', $id],
            ['date', today()]
        ])->get();

        return response()->json([
            'message' => true,
            'history' => ClientHistoryResource::collection($history)
        ]);
    }

}
