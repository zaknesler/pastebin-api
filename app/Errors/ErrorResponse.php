<?php

namespace App\Errors;

class ErrorResponse
{
    /**
     * The message of the error.
     *
     * @var string
     */
    protected $message;

    /**
     * The HTTP status code of the error.
     *
     * @var int
     */
    protected $statusCode;

    /**
     * A list of errors.
     *
     * @var array
     */
    private $errors;

    /**
     * Instantiate a new error instance.
     *
     * @param string|null $message
     * @param int|null $statusCode
     */
    public function __construct($message = null, $statusCode = null, $errors = null)
    {
        $this->message = $message;
        $this->statusCode = $statusCode;
        $this->errors = $errors;
    }

    /**
     * Get the message of the error.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Get the HTTP status code of the error.
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
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
