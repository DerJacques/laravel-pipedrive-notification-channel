<?php

namespace DerJacques\PipedriveNotifications\Resources;

use DerJacques\PipedriveNotifications\PipedriveResource;

class Note extends PipedriveResource
{
    protected $id;
    protected $content;
    protected $personId;
    protected $dealId;

    protected $pluralis = 'notes';
    protected $singularis = 'note';

    protected $required = [
        'content',
    ];

    public function id(int $id = null)
    {
        $this->id = $id;

        return $this;
    }

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

    public function isNew()
    {
        return is_null($this->id);
    }

    public function getId()
    {
        return $this->id;
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
