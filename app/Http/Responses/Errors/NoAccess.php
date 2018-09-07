<?php

namespace App\Http\Responses\Errors;

use App\Http\Responses\ApiResponse;

class NoAccess extends ApiResponse
{
    /**
     * The API response message to display.
     *
     * @var boolean
     */
    protected $message = 'You do not have access to this paste.';

    /**
     * The API response message to display.
     *
     * @var int
     */
    protected $statusCode = 403;

    /**
     * Mark API response as erroneous.
     *
     * @var bool
     */
    protected $isErroneous = true;
}
