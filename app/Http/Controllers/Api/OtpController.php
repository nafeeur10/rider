<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OtpRequest;
use App\Repositories\DriverRepository;
use App\Repositories\VerificationCodeRepository;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class OtpController extends Controller
{
    use ResponseTrait;

    public function __construct(private DriverRepository $driverRepository,
                                private VerificationCodeRepository $verificationCodeRepository) {}

    /**
     * @throws \Exception
     */
    public function otpGenerate(OtpRequest $otpRequest): JsonResponse {
        $verificationCode = $this->generateOtp($otpRequest->mobile);
        $content = $verificationCode->getContent();
        $result = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        $message = "Your OTP To Login is - ".$result['data']['otp'];
        return $this->dataResponse($result['data']['otp'], $message, 201);
    }

    /**
     * @throws \Exception
     */
    private function generateOtp($mobile): JsonResponse {
        $driver = $this->driverRepository->getFirstWhere('mobile', $mobile);
        # Driver Does not Have Any Existing OTP
        $verificationCode = $this->verificationCodeRepository->getFirstWhere('driver_id', $driver->id);
        $now = Carbon::now();

        if($verificationCode && $now->isBefore($verificationCode->expire_at)){
            return $this->dataResponse($verificationCode, 'Verification Code', 200);
        }
        return $this->dataResponse($this->verificationCodeRepository->create($this->makeOtpData($driver?->id)), 'OTP created successfully', 200);
    }

    /**
     * @throws \Exception
     */
    private function makeOtpData($driverId): array{
        return [
            'driver_id' => $driverId,
            'otp' => random_int(123456, 999999),
            'expire_at' => Carbon::now()->addMinutes(10)
        ];
    }
}
