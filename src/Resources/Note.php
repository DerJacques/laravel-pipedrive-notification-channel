<?php

namespace DerJacques\PipedriveNotifications\Resources;

use DerJacques\PipedriveNotifications\PipedriveResource;

class Note extends PipedriveResource
{
    protected $content;
    protected $personId;
    protected $dealId;

    protected $plural = 'notes';
    protected $singular = 'note';

    protected $required = [
        'content',
    ];

    public function content(string $content)
    {
        $this->content = $content;

        return $this;
    }

    public function deal(int $dealId = null)
    {
        $this->dealId = $dealId;

        return $this;
    }

    public function person(int $personId = null)
    {
        $this->personId = $personId;

        return $this;
    }

    public function toPipedriveArray()
    {
        $attributes = [
            'content'   => $this->content,
            'deal_id'   => $this->dealId,
            'person_id' => $this->personId,
        ];

        return array_filter($attributes, function ($element) {
            return ! is_null($element);
        });
    }
}
