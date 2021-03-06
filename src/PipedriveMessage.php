<?php

namespace DerJacques\PipedriveNotifications;

use Closure;
use DerJacques\PipedriveNotifications\Resources\Deal;
use DerJacques\PipedriveNotifications\Resources\Note;
use DerJacques\PipedriveNotifications\Resources\Activity;

class PipedriveMessage
{
    public $deals = [];
    public $activities = [];
    public $notes = [];

    public function deal(Closure $callback)
    {
        $this->deals[] = $deal = new Deal();

        $callback($deal);

        return $this;
    }

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
}
