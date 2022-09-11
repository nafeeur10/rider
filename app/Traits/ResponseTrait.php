<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

trait ResponseTrait
{
    public function response(string $message, int $code): JsonResponse {
        return response()->json([
            'message' => $message
        ], $code);
    }

    public function dataResponse(Model $data, string $message, int $statusCode): JsonResponse {
        return response()->json([
           'data' => $data,
           'message' => $message
        ], $statusCode);
    }
}
