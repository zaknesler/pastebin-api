<?php

namespace Tests\Unit\Responses;

use Exception;
use Tests\TestCase;
use App\Http\Resources\ApiResource;
use App\Http\Responses\ExceptionResponse;
use Illuminate\Validation\ValidationException;

class ExceptionResponseTest extends TestCase
{
    /** @test */
    function an_exception_response_can_be_made_from_an_exception()
    {
        $exception = new Exception('example exception');
        $exceptionResponse = new ExceptionResponse($exception);

        $this->assertEquals('example exception', $exceptionResponse->getMessage());
        $this->assertEquals(true, $exceptionResponse->getErroneous());
    }

    /** @test */
    function an_exception_can_return_a_default_status_code()
    {
        $exception = new Exception('example exception');
        $exceptionResponse = new ExceptionResponse($exception);

        $this->assertEquals(400, $exceptionResponse->getStatusCode());
    }
}
