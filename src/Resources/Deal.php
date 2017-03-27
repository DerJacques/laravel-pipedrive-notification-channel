<?php

namespace DerJacques\PipedriveNotifications\Resources;

use Closure;
use DerJacques\PipedriveNotifications\PipedriveResource;

class Deal extends PipedriveResource
{
    private $title;
    private $id;
    private $value;
    private $currency;
    private $visibleTo;
    private $stageId;
    private $status;
    private $userId;
    public $activities = [];
    public $notes = [];

    protected $hasMany = [
        'activities',
        'notes',
    ];

    protected $required = [
        'title',
    ];

    public function activity(Closure $callback)
    {
        $this->activities[] = $activity = new Activity();

        $callback($activity);

        return $this;
    }

    public function note(Closure $callback)
    {
        $this->notes[] = $note = new Note();

        $callback($note);

        return $this;
    }

    public function title(string $title = null)
    {
        $this->title = $title;

        return $this;
    }

    public function id(int $id = null)
    {
        $this->id = $id;

        return $this;
    }

    public function user(int $userId = null)
    {
        $this->userId = $userId;

        return $this;
    }

    public function value(float $value = null)
    {
        $this->value = $value;

        return $this;
    }

    public function currency(string $currency = null)
    {
        $this->currency = $currency;

        return $this;
    }

    public function visibleTo(int $visibleTo = null)
    {
        $this->visibleTo = $visibleTo;

        return $this;
    }

    public function stage(int $stageId = null)
    {
        $this->stageId = $stageId;

        return $this;
    }

    public function status(string $status = null)
    {
        $this->status = $status;

        return $this;
    }

    public function won()
    {
        $this->status = 'won';

        return $this;
    }

    public function lost()
    {
        $this->status = 'lost';

        return $this;
    }

    public function open()
    {
        $this->status = 'open';

        return $this;
    }

    public function toPipedriveArray()
    {
        $attributes = [
            'title'      => $this->title,
            'value'      => $this->value,
            'currency'   => $this->currency,
            'visible_to' => $this->visibleTo,
            'stage_id'   => $this->stageId,
            'status'     => $this->status,
            'user_id'    => $this->userId,
        ];

        return array_filter($attributes, function ($element) {
            return !is_null($element);
        });
    }

    public function isNew()
    {
        return is_null($this->id);
    }

    public function getId()
    {
        return $this->id;
    }
}
