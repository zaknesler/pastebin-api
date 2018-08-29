<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Errors\ErrorResponse;
use App\Errors\MustBeAuthenticated;
use App\Http\Resources\ErrorResource;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ErrorResponseTest extends TestCase
{
    /** @test */
    function an_error_response_can_set_a_status_code_and_a_message()
    {
        $error = new ErrorResponse(
            'Test error message.',
            404
        );

        $this->assertEquals('Test error message.', $error->getMessage());
        $this->assertEquals(404, $error->getStatusCode());
    }

    /** @test */
    function an_error_resource_can_return_an_error_response()
    {
        $error = new ErrorResponse(
            'Test error message.',
            404
        );

        $resource = (new ErrorResource($error))->resolve();

        $this->assertEquals('Test error message.', $resource['message']);
        $this->assertEquals(404, $resource['status']);
    }

    /** @test */
    function must_be_authenticated_error()
    {
        $resource = (new ErrorResource(new MustBeAuthenticated))->resolve();

        $this->assertEquals('You must be authenticated to create a private paste.', $resource['message']);
        $this->assertEquals(401, $resource['status']);
    }
}
