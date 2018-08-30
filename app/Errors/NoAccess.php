<?php

namespace App\Errors;

class NoAccess extends ErrorResponse
{
    /**
     * Get the message of the error.
     *
     * @return string
     */
    public function getMessage()
    {
        return 'You do not have access to this paste.';
    }

    /**
     * Get the HTTP status code of the error.
     *
     * @return int
     */
    public function getStatusCode()
    {
        return 403;
    }
}
