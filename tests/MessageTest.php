<?php

namespace DerJacques\PipedriveNotifications\Test;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Notifications\Notification;
use Mockery;
use DerJacques\PipedriveNotifications\PipedriveChannel;
use DerJacques\PipedriveNotifications\Resources\Deal;
use DerJacques\PipedriveNotifications\Resources\Note;
use DerJacques\PipedriveNotifications\Resources\Activity;
use DerJacques\PipedriveNotifications\PipedriveMessage;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{

    /**
     * @test
    */
    public function it_accepts_deals()
    {
        $message = new PipedriveMessage;
        $message->deal(function ($deal) {
            $deal->title('test');
        });

        $this->assertCount(1, $message->deals);
        $this->assertInstanceOf(Deal::class, $message->deals[0]);
    }

    /**
     * @test
    */
    public function it_accepts_activities()
    {
        $message = new PipedriveMessage;
        $message->activity(function ($activity) {
            $activity->subject('test');
        });

        $this->assertCount(1, $message->activities);
        $this->assertInstanceOf(Activity::class, $message->activities[0]);
    }

    /**
     * @test
    */
    public function it_accepts_notes()
    {
        $message = new PipedriveMessage;
        $message->note(function ($note) {
            $note->content('test');
        });

        $this->assertCount(1, $message->notes);
        $this->assertInstanceOf(Note::class, $message->notes[0]);
    }
}
