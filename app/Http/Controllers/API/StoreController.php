<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRequest;
use App\Http\Resources\StoreResource;
use App\Http\Services\StoreService;
use App\Models\Store;


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

    public function getFiveStores()
    {
        $stores = Store::limit(5)->get();

        return response()->json([
            'message' => true,
            'stores' => StoreResource::collection($stores)
        ]);
    }

    public function searchStore($store)
    {
        $clients = Store::where('name', 'like', '%' . $store . '%')->get();

        return response()->json([
            'message' => true,
            'clients' => StoreResource::collection($clients)
        ]);
    }
}
