<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DriverLoginRequest;
use App\Http\Requests\DriverRegistrationRequest;
use App\Models\Driver;
use App\Models\VerificationCode;
use App\Repositories\DriverRepository;
use App\Services\TokenManager;
use Carbon\Carbon;
use Illuminate\Hashing\HashManager;
use Illuminate\Http\JsonResponse;

class DriverController extends Controller
{
    public function __construct(private DriverRepository $driverRepository,
    private TokenManager $tokenManager,
    private HashManager $hash) {}

    public function registerDriver(DriverRegistrationRequest $registrationRequest) {
        $driver = $this->driverRepository->create($registrationRequest->all());
        return response()->json([
            'driver' => $driver,
            'message' => 'Driver created successfully',
            'token' => $this->tokenManager->createToken($driver)->plainTextToken,
        ], 201);
    }

    public function loginWithOtp(DriverLoginRequest $driverLoginRequest): JsonResponse {
        /** @var Driver|null $driver */
        $driver = $this->driverRepository->getFirstWhere('mobile', $driverLoginRequest->mobile);
        if (!$driver || !$this->hash->check($driverLoginRequest->password, $driver->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        return $this->validateDriverOtp($driverLoginRequest);
    }

    private function validateDriverOtp(DriverLoginRequest $driverLoginRequest): JsonResponse{
        #OTP Validation Logic
        $verificationCode   = VerificationCode::where('driver_id', $driverLoginRequest->driver_id)
            ->where('otp', $driverLoginRequest->otp)
            ->first();

        $now = Carbon::now();
        if (!$verificationCode) {
            return response()->json([
                'message' => 'OTP is not correct'
            ], 401);
        }elseif($verificationCode && $now->isAfter($verificationCode->expire_at)){
            return response()->json([
                'message' => 'OTP Expired'
            ], 401);
        }

        $driver = Driver::whereId($driverLoginRequest->driver_id)->first();

        if($driver){
            // Expire The OTP
            $verificationCode->update([
                'expire_at' => Carbon::now()
            ]);
            return response()->json([
                'message' => 'Successfully Logged In'
            ], 200);
        }

        return response()->json([
            'message' => 'Please try again'
        ], 401);
    }
}
