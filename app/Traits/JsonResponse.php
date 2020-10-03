<?php

namespace App\Traits;

use App\Exceptions\Code;
use Illuminate\Http\JsonResponse as Response;
use Symfony\Component\HttpFoundation\Response as HttpStatus;

Trait JsonResponse
{
    /**
     * @param int $code
     * @param string $message
     * @param int $statusCode
     * @param mixed $data
     * @return Response
     */
    public function fail(int $code = Code::SYSTEM, string $message = '', int $statusCode = 500, $data = [])
    {
        return response()->json([
            'code' => $code,
            'message' => $message,// 错误描述
            'data' => $data,// 错误详情
        ], $statusCode);
    }

    /**
     * @param array $data
     * @param string $message
     * @param int $statusCode
     *
     * @return Response
     */
    public function success($data = [], string $message = 'OK', int $statusCode = 200)
    {
        return response()->json([
            'code' => Code::SUCCESS,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    public function created($data = [], $message = 'Created')
    {
        return $this->success($data, $message, HttpStatus::HTTP_CREATED);
    }
}
