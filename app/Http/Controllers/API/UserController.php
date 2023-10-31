<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Http\Services\UserService;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(): JsonResponse
    {
        $users = User::all();

        return response()->json([
            'message' => true,
            'users' => UserResource::collection($users)
        ]);
    }

    public function store(UserRequest $request)
    {
        $data = $request->validated();

        $user = $this->userService->store($data);

        if ($user) {
            return response()->json([
                'message' => true,
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        $res = $this->userService->update($user, $request);

        return response([
            'message' => $res
        ]);
    }

    public function destroy($id) :JsonResponse
    {
        User::find($id)->delete();

        return response()->json([
           'message' => true
        ]);
    }
}
