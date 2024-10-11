<?php

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class CustomValidationException extends ValidationException
{
    public function render($request): JsonResponse
    {
        return new JsonResponse([
            'status' => false,
            'message' => 'Validation failed',
            'fails' => $this->validator->errors()->getMessages(),
        ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
    }
}
