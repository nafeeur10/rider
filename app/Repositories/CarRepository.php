<?php

namespace App\Repositories;

use App\Models\Car;

class CarRepository extends Repository
{
    public function __construct()
    {
        parent::__construct(Car::class);
    }

    public function create(array $data): Car {
        return $this->model->create($data);
    }
}
