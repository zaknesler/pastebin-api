<?php

namespace App\Http\Responses\Errors;

use App\Http\Responses\ApiResponse;

class MustBeAuthenticated extends ApiResponse
{
    /**
     * The API response message to display.
     *
     * @var boolean
     */
    protected $message = 'You must be authenticated to perform this action.';

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
