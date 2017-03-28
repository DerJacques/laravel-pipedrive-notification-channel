<?php

namespace DerJacques\PipedriveNotifications\Exceptions;

class FailedRequest extends \Exception
{
    public static function rejected($errorCode)
    {
        return new static('Pipedrive rejected the request with error code '.$errorCode);
    }

    public static function missingRequiredAttribute($attribute)
    {
        return new static('Missing required attribute: '.$attribute);
    }
}
