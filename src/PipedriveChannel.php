<?php

namespace DerJacques\PipedriveNotifications;

use GuzzleHttp\Client;
use Illuminate\Notifications\Notification;
use DerJacques\PipedriveNotifications\Exceptions\InvalidConfiguration;

class PipedriveChannel
{
    protected $client;
    protected $token;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function send($notifiable, Notification $notification)
    {
        $this->token = $notifiable->routeNotificationFor('Pipedrive');

        if (is_null($this->token)) {
            throw InvalidConfiguration::noTokenProvided();
        }

        $pipedriveMessage = $notification->toPipedrive($notifiable);

        foreach ($pipedriveMessage->deals as $deal) {
            $deal->setClient($this->client, $this->token);
            $deal->save();
        }

        foreach ($pipedriveMessage->activities as $activity) {
            $activity->setClient($this->client, $this->token);
            $activity->save();
        }
    }
}
