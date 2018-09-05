<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Errors\NoAccess;
use App\Errors\PasteExpired;
use App\Errors\ErrorResponse;
use App\Errors\MustBeAuthenticated;
use App\Http\Resources\ErrorResource;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ErrorResponseTest extends TestCase
{
    /** @test */
    function an_error_response_can_be_returned()
    {
        $error = new ErrorResponse(
            'Something went wrong.',
            404,
            ['foo', 'bar']
        );

        $resource = (new ErrorResource($error))->resolve();

        $this->assertEquals('Something went wrong.', $resource['message']);
        $this->assertEquals(404, $resource['status']);
        $this->assertEquals(['foo', 'bar'], $resource['errors']);
    }
    /** @test */
    function an_error_response_can_set_a_message()
    {
        $error = new ErrorResponse(
            'Test error message.'
        );

        $this->assertEquals('Test error message.', $error->getMessage());
    }

    /** @test */
    function an_error_response_can_set_a_status_code()
    {
        $error = new ErrorResponse(
            null,
            404
        );

        $this->assertEquals(404, $error->getStatusCode());
    }

    /** @test */
    function an_error_response_can_set_an_array_of_errors()
    {
        $errors = [
            'example' => [
                0 => 'Example error message',
            ],
        ];

         $error = new ErrorResponse(
            null,
            null,
            $errors
        );

        $this->assertEquals($errors, $error->getErrors());
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

        $this->assertEquals('You must be authenticated to perform this action.', $resource['message']);
        $this->assertEquals(401, $resource['status']);
    }

    /** @test */
    function no_access_error()
    {
        $resource = (new ErrorResource(new NoAccess))->resolve();

        $this->assertEquals('You do not have access to this paste.', $resource['message']);
        $this->assertEquals(403, $resource['status']);
    }

    /** @test */
    function paste_expired_error()
    {
        $resource = (new ErrorResource(new PasteExpired))->resolve();

        $this->assertEquals('This paste has expired.', $resource['message']);
        $this->assertEquals(410, $resource['status']);
    }
}
