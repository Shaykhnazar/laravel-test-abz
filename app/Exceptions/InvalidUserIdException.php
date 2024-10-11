<?php

namespace App\Exceptions;

use Exception;

class InvalidUserIdException extends Exception
{
    public function __construct($message = "The user with the requestedid does not exist", $code = 400)
    {
        parent::__construct($message, $code);
    }
}
