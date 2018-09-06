<?php

namespace App\Exceptions;

use Exception;
use App\Errors\ErrorResponse;
use Illuminate\Http\Response;
use App\Errors\MustBeAuthenticated;
use App\Http\Resources\ErrorResource;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
            if ($exception instanceof AuthenticationException) {
                return new ErrorResource(new MustBeAuthenticated);
            }

            return new ErrorResource(new ErrorResponse(
                $exception->getMessage(),
                $this->getStatusCodeFromException($exception),
                $this->getErrorsFromException($exception)
            ));
        }

        return parent::render($request, $exception);
    }

    /**
     * Get the status code of an exception if it has one.
     *
     * @param  \Exception $exception
     * @return int
     */
    protected function getStatusCodeFromException(Exception $exception)
    {
        if (method_exists($exception, 'getStatusCode')) {
            return $exception->getStatusCode();
        }

        return $exception->status ?? Response::HTTP_BAD_REQUEST;
    }

    /**
     * Fetch the validation errors from an exception if they exist.
     *
     * @param  \Exception $exception
     * @return array|null
     */
    protected function getErrorsFromException(Exception $exception)
    {
        if ($exception instanceof ValidationException) {
            return $exception->errors();
        }

        return null;
    }
}
