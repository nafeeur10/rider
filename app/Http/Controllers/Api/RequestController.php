<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRideRequest;
use App\Repositories\RequestRepository;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function __construct(private RequestRepository $requestRepository) {}

    public function submit(CustomerRideRequest $customerRideRequest) {

    }
}
