<?php

namespace DerJacques\PipedriveNotifications\Resources;

use DerJacques\PipedriveNotifications\Activity;
use DerJacques\PipedriveNotifications\PipedriveResource;
use Closure;

class Deal extends PipedriveResource {

    private $title;
    private $id;
    private $value;
    private $currency;
    private $visibleTo;
    private $stageId;
    private $status;
    private $customAttributes;
    private $userId;
    public $activities = [];

    protected $hasMany = [
        'activities'
    ];

    protected $required = [
        'title'
    ];

    public function title(string $title) {
        $this->title = $title;
        return $this;
    }

    public function id(int $id) {
        $this->id = $id;
        return $this;
    }

    public function user(int $userId) {
        $this->userId = $userId;
        return $this;
    }

    public function value(float $value) {
        $this->value = $value;
        return $this;
    }

    public function currency(string $currency) {
        $this->currency = $currency;
        return $this;
    }

    public function visibleTo(int $visibleTo) {
        $this->visibleTo = $visibleTo;
        return $this;
    }

    public function stage(int $stageId) {
        $this->stageId = $stageId;
        return $this;
    }

    public function status(string $status) {
        $this->status = $status;
        return $this;
    }

    public function won() {
        $this->status = 'won';
        return $this;
    }

    public function lost() {
        $this->status = 'lost';
        return $this;
    }

    public function toPipedriveArray() {
        $attributes = [
            'title' => $this->title,
            'value' => $this->value,
            'currency' => $this->currency,
            'visible_to' => $this->visibleTo,
            'stage_id' => $this->stageId,
            'status' => $this->status,
            'user_id' => $this->userId
        ];

        return array_filter($attributes, function($element) {
            return !is_null($element);
        });
    }

    public function isNew() {
        return is_null($this->id);
    }

    public function getId() {
        return $this->id;
    }

    public function activity(Closure $callback) {
        $this->activities[] = $activity = new Activity;

        $callback($activity);

        return $this;
    }
}
