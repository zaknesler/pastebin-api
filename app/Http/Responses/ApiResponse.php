<?php

namespace App\Http\Responses;

abstract class ApiResponse
{
    /**
     * The message that should be displayed.
     *
     * @var string
     */
    protected $message;

    /**
     * The HTTP status code the response should apply.
     *
     * @var int
     */
    protected $statusCode;

    /**
     * Mark an API response as erroneous.
     *
     * @var bool
     */
    protected $isErroneous;

    /**
     * A list of errors that should be included.
     *
     * @var array
     */
    protected $errors;

    /**
     * Get the response message.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Get the HTTP status code.
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Get the erroneous state of the response.
     *
     * @return bool
     */
    public function getErroneous()
    {
        return $this->isErroneous;
    }

    /**
     * Get the list of errors.
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
