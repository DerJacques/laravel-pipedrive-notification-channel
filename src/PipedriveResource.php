<?php

namespace DerJacques\PipedriveNotifications;

use DerJacques\PipedriveNotifications\Exceptions\FailedRequest;
use DerJacques\PipedriveNotifications\Exceptions\InvalidConfiguration;

class PipedriveResource
{
    protected $client = null;
    protected $token = null;
    protected $hasMany = [];
    protected $required = [];
    protected $plural = null;
    protected $singular = null;
    protected $id = null;

    const API_ENDPOINT = 'https://api.pipedrive.com/v1/';

    public function id(int $id = null)
    {
        $this->id = $id;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getPluralName()
    {
        return ! is_null($this->plural) ? $this->plural : strtolower((new \ReflectionClass($this))->getShortName()).'s';
    }

    public function getSingularName()
    {
        return ! is_null($this->singular) ? $this->singular : strtolower((new \ReflectionClass($this))->getShortName());
    }

    public function setClient($client, $token)
    {
        $this->client = $client;
        $this->token = $token;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function isNew()
    {
        return is_null($this->id);
    }

    public function save()
    {
        $this->checkConfiguration();

        $response = $this->sendRequest();

        if ($response->getStatusCode() >= 300 || $response->getStatusCode() <= 19) {
            throw FailedRequest::rejected($response->getStatusCode());
        }

        $this->saveRelationships(json_decode($response->getBody())->data->id);

        return $response;
    }

    protected function create(array $attributes)
    {
        foreach ($this->required as $requiredField) {
            if (! array_key_exists($requiredField, $attributes)) {
                throw FailedRequest::missingRequiredAttribute($requiredField);
            }
        }

        return $this->client->request('POST', self::API_ENDPOINT.$this->getPluralName().'?api_token='.$this->token, [
            'form_params' => $attributes,
        ]);
    }

    protected function update($attributes)
    {
        return $this->client->request('PUT', self::API_ENDPOINT.$this->getPluralName().'/'.$this->getId().'?api_token='.$this->token, [
            'form_params' => $attributes,
        ]);
    }

    private function saveRelationships($parentId)
    {
        foreach ($this->hasMany as $relationship) {
            foreach ($this->$relationship as $child) {
                $parent = $this->getSingularName();
                $child->$parent($parentId);
                $child->setClient($this->client, $this->token);
                $child->save();
            }
        }
    }

    private function checkConfiguration()
    {
        if (is_null($this->client) || is_null($this->token)) {
            throw InvalidConfiguration::noClientProvided();
        }
    }

    private function sendRequest()
    {
        if ($this->isNew()) {
            $response = $this->create($this->toPipedriveArray());
        } else {
            $response = $this->update($this->toPipedriveArray());
        }

        return $response;
    }

    public function toPipedriveArray()
    {
        return [];
    }
}
