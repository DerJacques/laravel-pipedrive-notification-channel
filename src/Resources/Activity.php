<?php

namespace DerJacques\PipedriveNotifications\Resources;
use DerJacques\PipedriveNotifications\PipedriveResource;
use DateTime;

class Activity extends PipedriveResource {

    protected $id;
    protected $subject;
    protected $type;
    protected $dueDate;
    protected $note;
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

    public function due($due)
    {
        if (! $due instanceof DateTime) {
            $due = new DateTime($due);
        }
        $this->dueDate = $due->format('Y-m-d');
        return $this;
    }

    public function note(string $note)
    {
        $this->note = $note;
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
            'note' => $this->note,
            'due_date' => $this->dueDate
        ];

        return array_filter($attributes, function($element) {
            return !is_null($element);
        });
    }
}
