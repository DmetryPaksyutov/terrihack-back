<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class FileNotFoundException extends Exception
{
    public function __construct($message = "The file was not found.", $code = 404)
    {
        parent::__construct($message, $code);
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'message' => $this->getMessage()
        ], Response::HTTP_NOT_FOUND);
    }
}
