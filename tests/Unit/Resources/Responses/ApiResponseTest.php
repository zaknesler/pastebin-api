<?php

namespace Tests\Unit\Resources\Responses;

use Tests\TestCase;
use App\Http\Resources\ApiResource;
use App\Http\Responses\CustomResponse;
use App\Http\Responses\Errors\NoAccess;
use App\Http\Responses\Errors\PasteExpired;
use App\Http\Responses\Errors\MustBeAuthenticated;

class ApiResponseTest extends TestCase
{
    /** @test */
    function api_resource_can_resolve_a_custom_response()
    {
        $customResponse = new CustomResponse('example message', 404, true, ['foo' => 'bar']);
        $apiResource = new ApiResource($customResponse);

        $this->assertEquals([
            'message' => 'example message',
            'status' => 404,
            'error' => true,
            'errors' => ['foo' => 'bar'],
        ], $apiResource->resolve());
    }

    /** @test */
    function api_resource_can_resolve_a_must_be_authenticated_response()
    {
        $apiResource = new ApiResource(new MustBeAuthenticated);

        $this->assertEquals([
            'message' => 'You must be authenticated to perform this action.',
            'status' => 401,
            'error' => true,
            'errors' => null,
        ], $apiResource->resolve());
    }

    /** @test */
    function api_resource_can_resolve_a_no_access_response()
    {
        $apiResource = new ApiResource(new NoAccess);

        $this->assertEquals([
            'message' => 'You do not have access to this paste.',
            'status' => 403,
            'error' => true,
            'errors' => null,
        ], $apiResource->resolve());
    }

    /** @test */
    function api_resource_can_resolve_a_paste_expired_response()
    {
        $apiResource = new ApiResource(new PasteExpired);

        $this->assertEquals([
            'message' => 'This paste has expired.',
            'status' => 410,
            'error' => true,
            'errors' => null,
        ], $apiResource->resolve());
    }
}
