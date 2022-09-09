<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ResponseTrait
{
    public function response($message, $code): JsonResponse {
        return response()->json([
            'message' => $message
        ], $code);
    }

    public function dataResponse($data, $message, $statusCode): JsonResponse {
        return response()->json([
           'data' => $data,
           'message' => $message
        ], $statusCode);
    }
}
