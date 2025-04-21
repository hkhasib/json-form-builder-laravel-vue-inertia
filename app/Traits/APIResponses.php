<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait APIResponses
{
    public function successResponse($data = [], $message = 'Operation Successful', $status_code = Response::HTTP_OK): JsonResponse
    {
        if (is_null($data)) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ], $status_code);
        }
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message,
        ], $status_code);
    }

    public function errorResponse($errors = [], $message = 'Operation Failed', $status_code = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        if (is_null($errors)) {
            return response()->json([
                'success' => false,
                'message' => $message,
            ], $status_code);
        }
        return response()->json([
            'success' => false,
            'errors' => $errors,
            'message' => $message,
        ], $status_code);
    }

    public function validationErrorResponse($validator, $message = 'Validation failed!', $status_code = Response::HTTP_UNPROCESSABLE_ENTITY): JsonResponse
    {
        $errors = method_exists($validator, 'errors') ? $validator->errors() : $validator;
        return $this->errorResponse($errors, $message, $status_code);
    }

    public function authFailedResponse($message = "Unauthorized", $status_code = Response::HTTP_UNAUTHORIZED): JsonResponse
    {
        return $this->errorResponse(null, $message, $status_code);
    }

    public function permissionDeniedResponse($message = "Permission Denied", $status_code = Response::HTTP_FORBIDDEN): JsonResponse
    {
        return $this->errorResponse(null, $message, $status_code);
    }

    public function notFoundResponse($message = 'Resource not found', $status_code = Response::HTTP_NOT_FOUND): JsonResponse
    {
        return $this->errorResponse(null, $message, $status_code);
    }


}
