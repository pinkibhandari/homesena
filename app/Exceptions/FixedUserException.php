<?php

namespace App\Exceptions;

use Exception;

class FixedUserException extends Exception
{
    public function render($request)
    {
        return response()->json([
            'code' => 422,
            'status' => false,
            'message' => $this->getMessage(),
            'data' => (object) []
        ], 422);
    }
}
