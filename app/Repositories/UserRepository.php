<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository extends Repository
{
    public function __construct()
    {
        parent::__construct(User::class);
    }

    public function create(array $data) {
        $data['password'] = Hash::make($data['password']);
        return $this->model->create($data);
    }
}
