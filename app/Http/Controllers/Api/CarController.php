<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CarRegistrationRequest;
use App\Repositories\CarRepository;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class CarController extends Controller
{
    use ResponseTrait;

    public function __construct(private CarRepository $carRepository) {}

    public function registerCar(CarRegistrationRequest $registrationRequest): JsonResponse {
        $car = $this->carRepository->create($registrationRequest->all());
        $message = 'Car registered successfully';
        return $this->dataResponse($car, $message, 201);
    }
}
