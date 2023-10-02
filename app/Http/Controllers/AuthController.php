<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Services\AuthService;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }


    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        $token = $this->authService->login($data);

        if (!$token) {
            return response()->json([
                'status' => false,
                'message' => 'Неверный логин или пароль'
            ]);
        }
        $user = User::where('login', $data['login'])->first();

        //       dd($data);
        return response()->json([
            'id' => $user->id,
            'name' => $user->fio,
            'status' => true,
            'token' => $token,
        ]);
    }
}
