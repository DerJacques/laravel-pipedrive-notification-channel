<?php

namespace DerJacques\PipedriveNotifications\Exceptions;

class InvalidConfiguration extends \Exception
{
    public static function noTokenProvided()
    {
        return new static('In order to interact with the Pipedrive API, your notofiable needs to provide an API token on `routeNotificationForPipedrive()`.');
    }
}
