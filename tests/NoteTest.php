<?php

namespace DerJacques\PipedriveNotifications\Test;

use DerJacques\PipedriveNotifications\Resources\Note;
use PHPUnit\Framework\TestCase;

class NoteTest extends TestCase
{
    protected $note;

    public function setUp()
    {
        parent::setUp();
        $this->note = new Note();
    }

    /**
     * @test
     */
    public function it_can_be_assigned_an_id()
    {
        $this->note->id(5);
        $this->assertEquals(5, $this->note->getId());
    }

    /**
     * @test
     */
    public function it_can_be_assigned_content()
    {
        $this->note->content('Content');
        $this->assertEquals('Content', $this->note->toPipedriveArray()['content']);
    }

    /**
     * @test
     */
    public function it_can_be_assigned_a_deal()
    {
        $this->note->deal(20);
        $this->assertEquals(20, $this->note->toPipedriveArray()['deal_id']);
    }

    /**
     * @test
     */
    public function it_can_be_assigned_a_person()
    {
        $this->note->person(10);
        $this->assertEquals(10, $this->note->toPipedriveArray()['person_id']);
    }

    /**
     * @test
     */
    public function it_can_show_if_it_is_new()
    {
        $this->note->id(null);
        $this->assertEquals(true, $this->note->isNew());

        $this->note->id(3);
        $this->assertEquals(false, $this->note->isNew());
    }
}
