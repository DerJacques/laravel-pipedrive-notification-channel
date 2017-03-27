<?php

namespace DerJacques\PipedriveNotifications\Test;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Notifications\Notification;
use Mockery;
use DerJacques\PipedriveNotifications\PipedriveChannel;
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
        $this->assertEquals('test', $message->deals[0]->toPipedriveArray()['title']);
    }
}
