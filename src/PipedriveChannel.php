<?php

namespace DerJacques\PipedriveNotifications;

use Illuminate\Notifications\Notification;
use GuzzleHttp\Client;

class PipedriveChannel {
    const API_ENDPOINT = 'https://api.pipedrive.com/v1/';

    /** @var Client */
    protected $client;
    protected $token;

    public function __construct(Client $client) {
        $this->client = $client;
    }

    public function send($notifiable, Notification $notification) {
        $this->token = $notifiable->routeNotificationFor('Pipedrive');

        if (is_null($this->token)) {
            throw \Exception();
        }

        $pipedriveMessage = $notification->toPipedrive($notifiable);

        if($pipedriveMessage->isNewDeal()) {
            $response = $this->createDeal($pipedriveMessage->toPipedriveArray());
        }

        if(!$pipedriveMessage->isNewDeal()) {
            $response = $this->updateDeal($pipedriveMessage->getDealId(), $pipedriveMessage->toPipedriveArray());
        }

        if ($response->getStatusCode() !== 200) {
            throw \Exception('Request failed');
        }
    }

    protected function createDeal(array $attributes) {
        if(!array_key_exists('title', $search)) {
            throw \Exception('Title required');
        }

        return $this->client->request('POST', self::API_ENDPOINT.'deals?api_token='.$this->token, [
            'form_params' => $attributes
        ]);
    }

    protected function updateDeal($dealId, $attributes) {
        return $this->client->request('PUT', self::API_ENDPOINT.'deals/'.$dealId.'?api_token='.$this->token, [
            'form_params' => $attributes
        ]);
    }
}
