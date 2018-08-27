<?php

namespace App\Errors;

class MustBeAuthenticated extends ErrorResponse
{
    /**
     * Get the message of the error.
     *
     * @return string
     */
    public function getMessage()
    {
        return 'You must be authenticated to create a private paste.';
    }

    /**
     * Get the HTTP status code of the error.
     *
     * @return int
     */
    public function getStatusCode()
    {
        return 401;
    }
}
