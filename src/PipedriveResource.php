<?php

namespace DerJacques\PipedriveNotifications;

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

    public function save($client, $token) {
        if($this->isNew()) {
            $response = $this->create($this->toPipedriveArray(), $client, $token);
        }

        if(!$this->isNew()) {
            $response = $this->update($this->toPipedriveArray(), $client, $token);
        }

        if ($response->getStatusCode() >= 300 || $response->getStatusCode() <= 19) {
            throw new Exception('Request failed');
        }

        $this->saveRelationships(json_decode($response->getBody())->data->id);

        return $response;
    }

    protected function create(array $attributes, $client, $token) {
        foreach($this->required as $requiredField) {
            if(!array_key_exists($requiredField, $attributes)) {
                throw new Exception($requiredField.' required');
            }
        }

        return $client->request('POST', self::API_ENDPOINT.$this->getPluralisName().'?api_token='.$token, [
            'form_params' => $attributes
        ]);
    }

    protected function update($attributes, $client, $token) {
        return $client->request('PUT', self::API_ENDPOINT.$this->getPluralisName().'/'.$this->getId().'?api_token='.$token, [
            'form_params' => $attributes
        ]);
    }

    private function saveRelationships($parentId) {
        foreach($this->hasMany as $relationship) {
            foreach($this->$relationship as $resource) {
                $parent = $this->getSingularisName();
                $resource->$parent($parentId);
                $resource->save($client, $token);
            }
        }
    }

}
