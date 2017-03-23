<?php

namespace DerJacques\PipedriveNotifications;

use Closure;
use DerJacques\PipedriveNotifications\Deal;
use DerJacques\PipedriveNotifications\Activity;

class PipedriveMessage {

    public $deals = [];
    public $activities = [];

    public function deal(Closure $callback) {
        $this->deals[] = $deal = new Deal;

        $callback($deal);

        return $this;
    }

    public function activity(Closure $callback) {
        $this->activities[] = $activity = new Activity;

        $callback($activity);

        return $this;
    }
}
