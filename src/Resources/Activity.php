<?php

namespace DerJacques\PipedriveNotifications\Resources;

use DateTime;
use DerJacques\PipedriveNotifications\PipedriveResource;

class Activity extends PipedriveResource
{
    protected $subject;
    protected $type;
    protected $dueDate;
    protected $note;
    protected $dealId;
    protected $userId;

    protected $plural = 'activities';
    protected $singular = 'activity';

    protected $required = [
        'type',
        'subject',
    ];

    public function subject(string $subject)
    {
        $this->subject = $subject;

        return $this;
    }

    public function type(string $type)
    {
        $this->type = $type;

        return $this;
    }

    public function due($due = null)
    {
        if (is_null($due)) {
            $this->dueDate = null;

            return $this;
        }

        if (! $due instanceof DateTime) {
            $due = new DateTime($due);
        }

        $this->dueDate = $due->format('Y-m-d');

        return $this;
    }

    public function note(string $note = null)
    {
        $this->note = $note;
    }

    public function deal(int $dealId = null)
    {
        $this->dealId = $dealId;

        return $this;
    }

    public function user(int $userId = null)
    {
        $this->userId = $userId;

        return $this;
    }

    public function toPipedriveArray()
    {
        $attributes = [
            'subject'  => $this->subject,
            'type'     => $this->type,
            'deal_id'  => $this->dealId,
            'user_id'  => $this->userId,
            'note'     => $this->note,
            'due_date' => $this->dueDate,
        ];

        return array_filter($attributes, function ($element) {
            return ! is_null($element);
        });
    }
}
