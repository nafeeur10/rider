<?php

namespace App\Repositories;

use App\Models\VerificationCode;
use Carbon\Carbon;

class VerificationCodeRepository extends Repository
{
    public function __construct()
    {
        parent::__construct(VerificationCode::class);
    }

    public function getOtpVerificationCode($driverId, $otp): int {
        return $this->model->where('driver_id', $driverId)
            ->where('otp', $otp)
            ->first();
    }

    public function updateOtpExpiryTime($verificationCodeId): void {
        $this->model->where('id', $verificationCodeId)
            ->update(['expire_at' => Carbon::now()]);
    }

    public function create($data) {
        return $this->model->create($data);
    }
}
