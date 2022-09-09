<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DriverLoginRequest;
use App\Http\Requests\DriverRegistrationRequest;
use App\Http\Requests\OtpRequest;
use App\Models\Driver;
use App\Models\VerificationCode;
use App\Repositories\DriverRepository;
use App\Repositories\VerificationCodeRepository;
use App\Services\TokenManager;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Hashing\HashManager;
use Illuminate\Http\JsonResponse;

class DriverController extends Controller
{
    use ResponseTrait;

    public function __construct(private DriverRepository $driverRepository,
    private TokenManager $tokenManager,
    private HashManager $hash,
    private VerificationCodeRepository $verificationCodeRepository) {}

    public function registerDriver(DriverRegistrationRequest $registrationRequest) {
        $driver = $this->driverRepository->create($registrationRequest->all());
        return response()->json([
            'driver' => $driver,
            'message' => 'Driver created successfully',
            'token' => $this->tokenManager->createToken($driver)->plainTextToken,
        ], 201);
    }

    public function driverLoginWithOtp(DriverLoginRequest $driverLoginRequest): JsonResponse {
        #OTP Validation Logic
        $verificationCode = $this->verificationCodeRepository->getOtpVerificationCode($driverLoginRequest->driver_id, $driverLoginRequest->otp);

        if (!$verificationCode) {
            return $this->response('OTP not correct', 401);
        }

        elseif($verificationCode && Carbon::now()->isAfter($verificationCode->expire_at)){
            return $this->response('OTP Expired', 401);
        }

        $driver = $this->driverRepository->getOneById($driverLoginRequest->driver_id);

        if($driver){
            $this->verificationCodeRepository->updateOtpExpiryTime($verificationCode->id); // Expire The OTP
            return $this->response('Successfully Logged In', 200);
        }

        return $this->response('Please try again', 401);
    }
}
