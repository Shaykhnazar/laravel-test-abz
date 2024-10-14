<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class InvalidUserIdException extends Exception
{
    protected $errors;

    /**
     * Constructor for InvalidUserIdException.
     *
     * @param string $message
     * @param array $errors
     * @param int $code
     */
    public function __construct($errors = [], string $message = "The user with the requested ID does not exist", $code = 400)
    {
        parent::__construct($message, $code);
        $this->errors = $errors;
    }

    /**
     * Render the response for the exception.
     *
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $this->getMessage(),
            'fails' => $this->errors,
        ], $this->getCode());
    }
}
