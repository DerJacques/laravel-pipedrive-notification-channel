<?php

namespace DerJacques\PipedriveNotifications;

use Illuminate\Notifications\Notification;
use GuzzleHttp\Client;
use Exception;

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
            throw new Exception('No Pipedrive Token provided');
        }

        $pipedriveMessage = $notification->toPipedrive($notifiable);

        foreach($pipedriveMessage->deals as $deal) {
            if($deal->isNew()) {
                $response = $this->createDeal($deal->toPipedriveArray());
            }

            if(!$deal->isNew()) {
                $response = $this->updateDeal($deal->getId(), $deal->toPipedriveArray());
            }

            if ($response->getStatusCode() >= 300 || $response->getStatusCode() <= 199) {
                throw new Exception('Request failed');
            }

            $responseBody = json_decode($response->getBody());
            $dealId = $responseBody->data->id;

            foreach($deal->activities as $activity) {

                $activity->deal($dealId);

                if($activity->isNew()) {
                    $response = $this->createActivity($activity->toPipedriveArray());
                }

                if(!$activity->isNew()) {
                    $response = $this->updateActivity($activity->getId(), $activity->toPipedriveArray());
                }

                if ($response->getStatusCode() >= 300 || $response->getStatusCode() <= 19) {
                    throw new Exception('Request failed');
                }
            }
        }
    }

    protected function createDeal(array $attributes) {
        if(!array_key_exists('title', $attributes)) {
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

    protected function createActivity(array $attributes) {
        if(!array_key_exists('subject', $attributes)) {
            throw \Exception('Subject required');
        }

        if(!array_key_exists('type', $attributes)) {
            throw \Exception('Type required');
        }

        return $this->client->request('POST', self::API_ENDPOINT.'activities?api_token='.$this->token, [
            'form_params' => $attributes
        ]);
    }

    protected function updateActivity($activityId, $attributes) {
        return $this->client->request('PUT', self::API_ENDPOINT.'activities/'.$activityId.'?api_token='.$this->token, [
            'form_params' => $attributes
        ]);
    }
}
