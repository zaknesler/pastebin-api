<?php

namespace App\Http\Responses\Errors;

use App\Http\Responses\ApiResponse;

class PasteExpired extends ApiResponse
{
    /**
     * The API response message to display.
     *
     * @var boolean
     */
    protected $message = 'This paste has expired.';

    /**
     * The API response message to display.
     *
     * @var int
     */
    protected $statusCode = 410;

    /**
     * Mark API response as erroneous.
     *
     * @var bool
     */
    protected $isErroneous = true;
}
