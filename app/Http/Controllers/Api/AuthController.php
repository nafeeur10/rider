<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\UserRegistrationRequest;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Repositories\UserRepository;
use App\Services\TokenManager;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Response;
use Illuminate\Hashing\HashManager;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(private UserRepository $userRepository,
    private HashManager $hash,
    private TokenManager $tokenManager,
    private ?Authenticatable $user)
    {

    }

    public function registerCustomer(UserRegistrationRequest $registrationRequest): JsonResponse
    {
        $user = $this->userRepository->create($registrationRequest->all());
        return response()->json([
            'user' => $user,
            'message' => 'User created successfully',
            'token' => $this->tokenManager->createToken($user)->plainTextToken,
        ], 201);
    }

    public function loginCustomer(UserLoginRequest $loginRequest): JsonResponse
    {
        $this->customValidationForEmailAndPhone($loginRequest->emailphone);

        /** @var User|null $user */
        $user = $this->userRepository->getFirstWhere('emailphone', $loginRequest->emailphone);

        if (!$user || !$this->hash->check($loginRequest->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        return response()->json([
            'token' => $this->tokenManager->createToken($user)->plainTextToken,
        ], 200);
    }

    public function logoutCustomer(): JsonResponse
    {
        $this->user?->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged Out'], 200);
    }

    private function customValidationForEmailAndPhone($emailPhone): JsonResponse
    {
        // Email Validation. If the string has @ then we are considering it is a email address.
        if (str_contains($emailPhone, '@') && isValidEmail($emailPhone) === false) {
            return response()->json(['message' => 'Incorrect Email or Phone Number 1'], 401);
        }

        if (str_contains($emailPhone, '@') && isValidEmail($emailPhone) === true) {
            return response()->json(true);
        }

        if(isValidMobile($emailPhone) === false) {
            return response()->json([ 'message' => 'Incorrect Email or Phone Number 2'], 401);
        }

        return response()->json(true);
    }
}
