<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

/**
 * Trait ResponseTrait
 * @package App\Traits
 */
trait ResponseTrait
{
    /**
     * @param array $data
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseSuccess(array $data = [], string $message = 'success', int $code = 200): JsonResponse
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ]);
    }

    /**
     * @param string $message
     * @param array $data
     * @param int $httpStatusCode
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseError(string $message = 'error', array $data = [], int $httpStatusCode = 500, int $code = 500): JsonResponse
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'error' => $data,
        ], $httpStatusCode);
    }
}
