<?php

namespace DerJacques\PipedriveNotifications;

use Illuminate\Notifications\Notification;
use GuzzleHttp\Client;

class PipedriveChannel {
    const API_ENDPOINT = 'https://api.pipedrive.com/v1/';

    /** @var Client */
    protected $client;

    public function __construct(Client $client) {
        $this->client = $client;
    }

    public function send($notifiable, Notification $notification) {
        print "hello";
    }
}
