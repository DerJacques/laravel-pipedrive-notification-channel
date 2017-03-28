<?php

namespace DerJacques\PipedriveNotifications\Exceptions;

class InvalidConfiguration extends \Exception
{
    public static function noTokenProvided()
    {
        return new static('In order to interact with the Pipedrive API, your notofiable needs to provide an API token on `routeNotificationForPipedrive()`.');
    }

    public static function noClientProvided()
    {
        return new static('Before saving a Pipedrive resource, you need to use `$resource->setClient($client, $token)` to set both request client (e.g. Guzzle) and a Pipedrive token.');
    }

    public static function noToPipedriveMethod()
    {
        return new static('Notification has no `toPipedrive` method.');
    }
}
