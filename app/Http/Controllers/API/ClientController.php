<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Http\Resources\ClientResource;
use App\Http\Services\ClientService;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function Laravel\Prompts\alert;

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
            'clients' => $clients
        ]);
    }

    public function show($id) {
        $client = $this->clientService->show($id);

        return response()->json([
            'client' => new ClientResource($client)
        ]);
    }

    public function store(ClientRequest $request) {
        $data = $request->validated();
        $client = $this->clientService->store($data);

        return response()->json([
            'status' => true,
            'client' => new ClientResource($client)
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


}
