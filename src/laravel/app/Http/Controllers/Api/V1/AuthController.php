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

    /**
     * Authenticates user by email and password, returns bearer token.
     * @group Authorisation
     * @unauthenticated
     * @response 200 {"message": "Logged in successfully.", "status": 200, "data": {"token": "15|9Y7nYKbtXVbRguDRIvg0DIDjQ9Uneg3CF1v0GAg8c45704c3"}}
     * @response 401 {"message": "Invalid credentials."}
     *
     * @param ApiLoginRequest $request
     * @return JsonResponse
     */
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
                    ->createToken('API Token',
                        $user->permissionsToArray(),
                        now()->addWeek())
                    ->plainTextToken,
            ]
        );
    }

    /**
     * User logout (token expires immediately)
     * @group Authorisation
     * @response 200 {"message": "Logged out successfully.","status": 200,"data": []}
     * @response 401 {"message": "Unauthenticated."}
     *
     * @return JsonResponse
     *
     */
    public function logout(): JsonResponse
    {
        Auth::user()->currentAccessToken()->delete();

        return $this->ok('Logged out successfully.');
    }
}
