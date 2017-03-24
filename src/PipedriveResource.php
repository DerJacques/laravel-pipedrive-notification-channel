<?php

namespace DerJacques\PipedriveNotifications;

use GuzzleHttp\Client;

class PipedriveResource {

    public $client;

    protected $hasMany = [];
    protected $required = [];

    const API_ENDPOINT = 'https://api.pipedrive.com/v1/';

    private function getPluralisName() {
        return isset($this->pluralis) ? $this->pluralis : strtolower((new \ReflectionClass($this))->getShortName()).'s';
    }

    private function getSingularisName() {
        return isset($this->singularis) ? $this->singularis : strtolower((new \ReflectionClass($this))->getShortName());
    }

    public function setClient($client, $token) {
        $this->client = $client;
        $this->token = $token;
    }

    public function save() {
        if($this->isNew()) {
            $response = $this->create($this->toPipedriveArray(), $this->client, $this->token);
        }

        if(!$this->isNew()) {
            $response = $this->update($this->toPipedriveArray(), $this->client, $this->token);
        }

        if ($response->getStatusCode() >= 300 || $response->getStatusCode() <= 19) {
            throw new Exception('Request failed');
        }

        $this->saveRelationships(json_decode($response->getBody())->data->id);

        return $response;
    }

    protected function create(array $attributes) {
        foreach($this->required as $requiredField) {
            if(!array_key_exists($requiredField, $attributes)) {
                throw new Exception($requiredField.' required');
            }
        }

        return $this->client->request('POST', self::API_ENDPOINT.$this->getPluralisName().'?api_token='.$this->token, [
            'form_params' => $attributes
        ]);
    }

    protected function update($attributes) {
        return $this->client->request('PUT', self::API_ENDPOINT.$this->getPluralisName().'/'.$this->getId().'?api_token='.$this->token, [
            'form_params' => $attributes
        ]);
    }

    private function saveRelationships($parentId) {
        foreach($this->hasMany as $relationship) {
            foreach($this->$relationship as $child) {
                $parent = $this->getSingularisName();
                $child->$parent($parentId);
                $child->setClient($this->client, $this->token);
                $child->save();
            }
        }
    }

}
