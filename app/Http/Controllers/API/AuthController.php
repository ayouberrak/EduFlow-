<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\auth\LoginRequest;
use App\Http\Requests\auth\RegisterRequest;
use App\Repository\interfaces\UserRepositoryInterface;


class AuthController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function register(RegisterRequest $request) {
        $user = $this->userRepository->create($request->validated());

        $token = auth()->guard('api')->login($user);
         

        return response()->json([
            'message'=>'User created successfully',
            'user'=>$user,
            'token'=>$token
        ], 201);
    }


    public function login(LoginRequest $request) {
        $credentials = $request->validated();

        if (! $token = auth()->guard('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'message'=>'User logged in successfully',
            'user'=>$this->userRepository->findByEmail($credentials['email']),
            'token'=>$token
        ], 200);
    }


        public function logout() {
        auth()->guard('api')->logout();
        return response()->json([
            'message'=>'User logged out successfully'
        ], 200);
    }

}
