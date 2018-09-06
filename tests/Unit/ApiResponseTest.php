<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Resources\ApiResource;
use App\Http\Responses\CustomResponse;
use App\Http\Responses\Errors\NoAccess;
use App\Http\Responses\Errors\PasteExpired;
use Illuminate\Foundation\Testing\WithFaker;
use App\Http\Responses\Errors\MustBeAuthenticated;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiResponseTest extends TestCase
{
    function setUp()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    function an_error_response_can_be_returned()
    {
        $error = new CustomResponse(
            'Something went wrong.',
            404,
            ['foo', 'bar']
        );

        $resource = (new ApiResource($error))->resolve();

        $this->assertEquals('Something went wrong.', $resource['message']);
        $this->assertEquals(404, $resource['status']);
        $this->assertEquals(['foo', 'bar'], $resource['errors']);
    }
    /** @test */
    function an_error_response_can_set_a_message()
    {
        $error = new CustomResponse(
            'Test error message.'
        );

        $this->assertEquals('Test error message.', $error->getMessage());
    }

    /** @test */
    function an_error_response_can_set_a_status_code()
    {
        $error = new CustomResponse(
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

         $error = new CustomResponse(
            null,
            null,
            $errors
        );

        $this->assertEquals($errors, $error->getErrors());
    }

    /** @test */
    function an_error_resource_can_return_an_error_response()
    {
        $error = new CustomResponse(
            'Test error message.',
            404
        );

        $resource = (new ApiResource($error))->resolve();

        $this->assertEquals('Test error message.', $resource['message']);
        $this->assertEquals(404, $resource['status']);
    }

    /** @test */
    function must_be_authenticated_error()
    {
        $resource = (new ApiResource(new MustBeAuthenticated))->resolve();

        $this->assertEquals('You must be authenticated to perform this action.', $resource['message']);
        $this->assertEquals(401, $resource['status']);
    }

    /** @test */
    function no_access_error()
    {
        $resource = (new ApiResource(new NoAccess))->resolve();

        $this->assertEquals('You do not have access to this paste.', $resource['message']);
        $this->assertEquals(403, $resource['status']);
    }

    /** @test */
    function paste_expired_error()
    {
        $resource = (new ApiResource(new PasteExpired))->resolve();

        $this->assertEquals('This paste has expired.', $resource['message']);
        $this->assertEquals(410, $resource['status']);
    }
}
