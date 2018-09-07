<?php

namespace App\Http\Responses;

use Exception;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class ExceptionResponse extends ApiResponse
{
    /**
     * The exception to be displayed.
     *
     * @var \Exception
     */
    private $exception;

    /**
     * Mark API response as erroneous.
     *
     * @var boolean
     */
    protected $isErroneous = true;

    /**
     * Instantiate a new exception response.
     *
     * @param \Exception  $exception
     */
    public function __construct(Exception $exception)
    {
        $this->exception = $exception;
    }

    /**
     * Get the response message.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->exception->getMessage();
    }

    /**
     * Get the HTTP status code.
     *
     * @return int
     */
    public function getStatusCode()
    {
        if (method_exists($this->exception, 'getStatusCode')) {
            return $this->exception->getStatusCode();
        }

        return $this->exception->status ?? Response::HTTP_BAD_REQUEST;
    }

    /**
     * Get the list of errors.
     *
     * @return array
     */
    public function getErrors()
    {
        if ($this->exception instanceof ValidationException) {
            return $this->exception->errors();
        }

        return null;
    }
}
