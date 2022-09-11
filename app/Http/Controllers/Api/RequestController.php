<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRideRequest;
use App\Http\Requests\DriverStatusRequest;
use App\Repositories\RequestRepository;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RequestController extends Controller
{

    use ResponseTrait;

    public function __construct(private RequestRepository $requestRepository) {}

    public function submit(CustomerRideRequest $customerRideRequest): JsonResponse {
        $request = $this->requestRepository->create($customerRideRequest->all());
        $message = 'We are finding driver for you!';
        return $this->dataResponse($request, $message, 201);
    }

    public function accept(DriverStatusRequest $statusRequest, $id): JsonResponse {
        $getTheRequest = $this->requestRepository->getOneById($id);
        $this->requestRepository->getFirstWhere('id', $getTheRequest?->id)->update($statusRequest->all());
        $message = 'Thanks for accepting request!';
        return $this->response($message, 200);
    }
}
