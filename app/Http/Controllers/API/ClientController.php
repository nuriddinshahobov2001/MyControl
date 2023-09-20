<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Http\Resources\ClientResource;
use App\Http\Services\ClientService;
use App\Models\Client;
use Illuminate\Http\Request;
use function Laravel\Prompts\alert;

class ClientController extends Controller
{
    private ClientService $clientService;
    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    public function index() {
        $clients = Client::get();
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
