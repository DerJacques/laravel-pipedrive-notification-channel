<?php

namespace DerJacques\PipedriveNotifications\Test;

use DerJacques\PipedriveNotifications\Resources\Activity;
use PHPUnit\Framework\TestCase;

class ActivityTest extends TestCase
{
    protected $activity;

    public function setUp()
    {
        parent::setUp();
        $this->activity = new Activity();
    }

    /**
     * @test
     */
    public function it_can_be_assigned_an_id()
    {
        $this->activity->id(5);
        $this->assertEquals(5, $this->activity->getId());
    }

    /**
     * @test
     */
    public function it_can_be_assigned_a_subject()
    {
        $this->activity->subject('Subject');
        $this->assertEquals('Subject', $this->activity->toPipedriveArray()['subject']);
    }

    /**
     * @test
     */
    public function it_can_be_assigned_a_type()
    {
        $this->activity->type('call');
        $this->assertEquals('call', $this->activity->toPipedriveArray()['type']);
    }

    /**
     * @test
     */
    public function it_can_be_assigned_a_due_date_from_a_string()
    {
        $tomorrowDate = new \DateTime('tomorrow');
        $this->activity->due('tomorrow');

        $this->assertEquals($tomorrowDate->format('Y-m-d'), $this->activity->toPipedriveArray()['due_date']);
    }

    /**
     * @test
     */
    public function it_can_be_assigned_a_due_date_from_datetime()
    {
        $date = new \DateTime('tomorrow');
        $this->activity->due($date);

        $this->assertEquals($date->format('Y-m-d'), $this->activity->toPipedriveArray()['due_date']);
    }

    /**
     * @test
     */
    public function it_can_be_assigned_a_note()
    {
        $this->activity->note('Note');
        $this->assertEquals('Note', $this->activity->toPipedriveArray()['note']);
    }

    /**
     * @test
     */
    public function it_can_be_assigned_a_user()
    {
        $this->activity->user(4);
        $this->assertEquals(4, $this->activity->toPipedriveArray()['user_id']);
    }

    /**
     * @test
     */
    public function it_can_show_if_it_is_new()
    {
        $this->activity->id(null);
        $this->assertEquals(true, $this->activity->isNew());

        $this->activity->id(3);
        $this->assertEquals(false, $this->activity->isNew());
    }
}
