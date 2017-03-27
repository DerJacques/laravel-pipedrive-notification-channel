<?php

namespace DerJacques\PipedriveNotifications\Test;

use PHPUnit\Framework\TestCase;
use DerJacques\PipedriveNotifications\Resources\Deal;
use DerJacques\PipedriveNotifications\Resources\Note;
use DerJacques\PipedriveNotifications\Resources\Activity;

class DealTest extends TestCase
{

    protected $deal;

    public function setUp()
    {
        parent::setUp();
        $this->deal = new Deal;
    }

    /**
    * @test
    */
    public function it_can_be_assigned_an_id()
    {
        $this->deal->id(5);
        $this->assertEquals(5, $this->deal->getId());
    }

    /**
    * @test
    */
    public function it_can_be_assigned_a_title()
    {
        $this->deal->title('Title');
        $this->assertEquals('Title', $this->deal->toPipedriveArray()['title']);
    }


    /**
    * @test
    */
    public function it_can_be_assigned_a_value()
    {
        $this->deal->value(100);
        $this->assertEquals(100, $this->deal->toPipedriveArray()['value']);
    }

    /**
    * @test
    */
    public function it_can_be_assigned_a_currency()
    {
        $this->deal->currency('EUR');
        $this->assertEquals('EUR', $this->deal->toPipedriveArray()['currency']);
    }

    /**
    * @test
    */
    public function it_can_be_assigned_a_visibility()
    {
        $this->deal->visibleTo(3);
        $this->assertEquals(3, $this->deal->toPipedriveArray()['visible_to']);
    }

    /**
    * @test
    */
    public function it_can_be_assigned_a_stage()
    {
        $this->deal->stage(2);
        $this->assertEquals(2, $this->deal->toPipedriveArray()['stage_id']);
    }

    /**
    * @test
    */
    public function it_can_be_assigned_a_status_manually()
    {
        $this->deal->status('open');
        $this->assertEquals('open', $this->deal->toPipedriveArray()['status']);
    }

    /**
    * @test
    */
    public function it_can_be_won()
    {
        $this->deal->won();
        $this->assertEquals('won', $this->deal->toPipedriveArray()['status']);
    }

    /**
    * @test
    */
    public function it_can_be_lost()
    {
        $this->deal->lost();
        $this->assertEquals('lost', $this->deal->toPipedriveArray()['status']);
    }

    /**
    * @test
    */
    public function it_can_be_opened()
    {
        $this->deal->open();
        $this->assertEquals('open', $this->deal->toPipedriveArray()['status']);
    }

    /**
    * @test
    */
    public function it_can_show_if_it_is_new()
    {
        $this->deal->id(null);
        $this->assertEquals(true, $this->deal->isNew());

        $this->deal->id(3);
        $this->assertEquals(false, $this->deal->isNew());
    }

    /**
    * @test
    */
    public function it_can_be_assigned_a_user()
    {
        $this->deal->user(5);
        $this->assertEquals(5, $this->deal->toPipedriveArray()['user_id']);
    }

    /**
     * @test
    */
    public function it_accepts_activities()
    {
        $this->deal->activity(function ($activity) {
            $activity->subject('activity');
        });

        $this->assertCount(1, $this->deal->activities);
        $this->assertInstanceOf(Activity::class, $this->deal->activities[0]);
    }

    /**
     * @test
    */
    public function it_accepts_notes()
    {
        $this->deal->note(function ($note) {
            $note->content('test');
        });

        $this->assertCount(1, $this->deal->notes);
        $this->assertInstanceOf(Note::class, $this->deal->notes[0]);
    }

}
