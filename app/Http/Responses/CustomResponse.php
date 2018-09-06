<?php

namespace App\Http\Responses;

class CustomResponse extends ApiResponse
{
    /**
     * Instantiate a new API response instance.
     *
     * @param string|null $message
     * @param int|null $statusCode
     * @param bool|null $isErroneous
     * @param array|null $errors
     */
    public function __construct($message = null, $statusCode = null, $isErroneous = null, $errors = null)
    {
        $this->message = $message;
        $this->statusCode = $statusCode;
        $this->isErroneous = $isErroneous;
        $this->errors = $errors;
    }
}
