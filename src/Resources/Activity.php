<?php

namespace DerJacques\PipedriveNotifications\Resources;
use DerJacques\PipedriveNotifications\PipedriveResource;

class Activity extends PipedriveResource {

    protected $id;
    protected $subject;
    protected $type;
    protected $dealId;
    protected $userId;

    protected $pluralis = 'activities';
    protected $singularis = 'activity';

    protected $required = [
        'type',
        'subject'
    ];

    public function id (int $id) {
        $this->id = $id;
        return $this;
    }

    public function subject(string $subject) {
        $this->subject = $subject;
        return $this;
    }

    public function type(string $type) {
        $this->type = $type;
        return $this;
    }

    public function deal(int $dealId) {
        $this->dealId = $dealId;
        return $this;
    }

    public function user(int $userId) {
        $this->userId = $userId;
        return $this;
    }

    public function isNew() {
        return is_null($this->id);
    }

    public function getId() {
        return $this->id;
    }

    public function toPipedriveArray() {
        $attributes = [
            'subject' => $this->subject,
            'type' => $this->type,
            'deal_id' => $this->dealId,
            'user_id' => $this->userId
        ];

        return array_filter($attributes, function($element) {
            return !is_null($element);
        });
    }
}
