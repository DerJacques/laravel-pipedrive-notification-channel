<?php

namespace DerJacques\PipedriveNotifications\Resources;
use DerJacques\PipedriveNotifications\PipedriveResource;
use DateTime;

class Activity extends PipedriveResource {

    protected $id;
    protected $subject;
    protected $type;
    protected $due;
    protected $dealId;
    protected $userId;

    protected $pluralis = 'activities';
    protected $singularis = 'activity';

    protected $notes = [];

    protected $required = [
        'type',
        'subject'
    ];

    public function note(Closure $callback) {
        $this->notes[] = $note = new Note;

        $callback($note);

        return $this;
    }

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

    public function due($due)
    {
        if (! $due instanceof DateTime) {
            $due = new DateTime($due);
        }
        $this->due = $due->format('Y-m-d');
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
            'user_id' => $this->userId,
            'due' => $this->due
        ];

        return array_filter($attributes, function($element) {
            return !is_null($element);
        });
    }
}
