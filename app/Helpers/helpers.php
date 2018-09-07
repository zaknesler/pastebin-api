<?php

use App\Http\Resources\ApiResource;
use App\Http\Responses\ApiResponse;
use App\Http\Responses\CustomResponse;

if (!function_exists('apiResponse')) {
    /**
     * Return a custom api response.
     *
     * @param  string  $args
     * @return \Illuminate\Http\Response
     */
    function apiResponse(...$args)
    {
        if ($args[0] instanceof ApiResponse) {
            return new ApiResource($args[0]);
        }

        return new ApiResource(new CustomResponse(...$args));
    }
}
