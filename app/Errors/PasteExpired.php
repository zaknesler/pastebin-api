<?php

namespace App\Errors;

class PasteExpired extends ErrorResponse
{
    /**
     * Get the message of the error.
     *
     * @return string
     */
    public function getMessage()
    {
        return 'This paste has expired.';
    }

    /**
     * Get the HTTP status code of the error.
     *
     * @return int
     */
    public function getStatusCode()
    {
        return 410;
    }
}
