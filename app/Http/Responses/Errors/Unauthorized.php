<?php

namespace App\Http\Responses\Errors;

use App\Http\Responses\ApiResponse;

class Unauthorized extends ApiResponse
{
    /**
     * The API response message to display.
     *
     * @var boolean
     */
    protected $message = 'You are unauthorized to do this.';

    /**
     * The API response message to display.
     *
     * @var int
     */
    protected $statusCode = 401;

    /**
     * Mark API response as erroneous.
     *
     * @var bool
     */
    protected $isErroneous = true;
}
