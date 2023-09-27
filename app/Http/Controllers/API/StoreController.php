<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Http\Requests\StoreRequest;
use App\Http\Resources\ClientResource;
use App\Http\Resources\StoreResource;
use App\Http\Services\ClientService;
use App\Http\Services\StoreService;
use App\Models\Client;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use function Laravel\Prompts\alert;

class StoreController extends Controller
{
    private StoreService $storeService;
    public function __construct(StoreService $storeService)
    {
        $this->storeService = $storeService;
    }

    public function index() {
        $stores = Store::get();
        return response([
            'message' => true,
            'stores' => StoreResource::collection($stores)
        ]);
    }

    public function show($id) {
        $store = $this->storeService->show($id);

        return response()->json([
            'store' => $store
        ]);
    }

    public function store(StoreRequest $request) {
        $data = $request->validated();
        $store = $this->storeService->store($data);

        return response()->json([
            'status' => true,
            'store' => $store
        ]);
    }

    public function update(StoreRequest $request, $id) {
        $data = $request->validated();
        $store = $this->storeService->update($id, $data);

        return response()->json([
            'status' => true,
            'client' => $store
        ]);
    }

    public function destroy($id) {
        $message = $this->storeService->delete($id);

        return response()->json([
            'status' => $message ?? false,
        ]);
    }


}
