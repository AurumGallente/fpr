<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ApiLoginRequest;
use App\Traits\ApiResponses;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use \Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    use ApiResponses;

    public function login(ApiLoginRequest $request): JsonResponse
    {
        $request->validated($request->all());

        if(!Auth::attempt($request->only(['email', 'password'])))
        {
            return $this->error('Invalid credentials', 401);
        }

        $user = User::firstWhere('email', $request->input('email'));

        return $this->ok(
            'Logged in successfully.',
            [
                'token' => $user
                    ->createToken('API Token', ['*'], now()->addWeek())
                    ->plainTextToken,
            ]
        );
    }

    public function logout(): JsonResponse
    {
        Auth::user()->currentAccessToken()->delete();

        return $this->ok('Logged out successfully.');
    }
}
