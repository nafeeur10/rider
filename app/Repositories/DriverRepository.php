<?php

namespace App\Repositories;

use App\Models\Driver;
use App\Models\VerificationCode;
use Illuminate\Support\Facades\Hash;

class DriverRepository extends  Repository
{
    public function __construct()
    {
        parent::__construct(Driver::class);
    }

    public function create(array $data) {
        $data['password'] = Hash::make($data['password']);
        return $this->model->create($data);
    }
}
