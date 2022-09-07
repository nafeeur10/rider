<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Repositories\UserRepository;
use App\Services\TokenManager;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Response;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Hashing\HashManager;

class AuthController extends Controller
{
    use ThrottlesLogins;

    public function __construct(private UserRepository $userRepository,
    private HashManager $hash,
    private TokenManager $tokenManager,
    private ?Authenticatable $user)
    {
        
    }

    public function login(UserLoginRequest $request)
    {
        /** @var User|null $user */
        $user = $this->userRepository->getFirstWhere('emailphone', $request->emailphone);

        if (!$user || !$this->hash->check($request->password, $user->password)) {
            abort(Response::HTTP_UNAUTHORIZED, 'Invalid credentials');
        }

        return response()->json([
            'token' => $this->tokenManager->createToken($user)->plainTextToken,
        ]);
    }

    public function logout()
    {
        $this->user?->currentAccessToken()->delete(); // @phpstan-ignore-line

        return response()->noContent();
    }
}
