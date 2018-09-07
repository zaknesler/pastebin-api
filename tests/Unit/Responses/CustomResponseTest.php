<?php

namespace Tests\Unit\Responses;

use Tests\TestCase;
use App\Http\Resources\ApiResource;
use App\Http\Responses\ApiResponse;
use App\Http\Responses\CustomResponse;

class CustomResponseTest extends TestCase
{
    /** @test */
    function a_custom_response_extends_base_api_response()
    {
        $customResponse = new CustomResponse();

        $this->assertTrue($customResponse instanceof ApiResponse);
    }

    /** @test */
    function a_custom_response_can_assign_a_message()
    {
        $customResponse = new CustomResponse('example message');

        $this->assertEquals('example message', $customResponse->getMessage());
    }

    /** @test */
    function a_custom_response_can_assign_a_status_code()
    {
        $customResponse = new CustomResponse(null, 404);

        $this->assertEquals(404, $customResponse->getStatusCode());
    }

    /** @test */
    function a_custom_response_can_be_erroneous()
    {
        $customResponse = new CustomResponse(null, null, true);

        $this->assertEquals(true, $customResponse->getErroneous());
    }

    /** @test */
    function a_custom_response_can_assign_an_array_of_errors()
    {
        $customResponse = new CustomResponse(null, null, null, ['foo' => 'bar']);

        $this->assertEquals(['foo' => 'bar'], $customResponse->getErrors());
    }
}
