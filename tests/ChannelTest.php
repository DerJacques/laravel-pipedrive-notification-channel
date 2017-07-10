<?php

namespace DerJacques\PipedriveNotifications\Test;

use Mockery;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Illuminate\Notifications\Notification;
use DerJacques\PipedriveNotifications\PipedriveChannel;
use DerJacques\PipedriveNotifications\PipedriveMessage;
use DerJacques\PipedriveNotifications\Exceptions\InvalidConfiguration;

class ChannelTest extends TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * @test
     */
    public function it_can_create_and_update_deals_and_activities()
    {
        $response = new Response(200, ['Content-Type' => 'application/json'], '{"data" : { "id" : 1 }}');
        $client = Mockery::mock(Client::class);

        $client->shouldReceive('request')
            ->once()
            ->with('POST', 'https://api.pipedrive.com/v1/deals?api_token=PipedriveToken',
                [
                    'form_params' => [
                        'stage_id' => 1,
                        'title'    => 'new deal',
                    ],
                ])
            ->andReturn($response);

        $client->shouldReceive('request')
            ->once()
            ->with('POST', 'https://api.pipedrive.com/v1/activities?api_token=PipedriveToken',
                [
                    'form_params' => [
                        'deal_id' => 1,
                        'subject' => 'Call Jane',
                        'type'    => 'call',
                    ],
                ])
            ->andReturn($response);

        $client->shouldReceive('request')
            ->once()
            ->with('PUT', 'https://api.pipedrive.com/v1/activities/3?api_token=PipedriveToken',
                [
                    'form_params' => [
                        'deal_id' => 1,
                        'subject' => 'Email Joe',
                        'type'    => 'mail',
                    ],
                ])
            ->andReturn($response);

        $client->shouldReceive('request')
            ->once()
            ->with('POST', 'https://api.pipedrive.com/v1/notes?api_token=PipedriveToken',
                [
                    'form_params' => [
                        'deal_id' => 1,
                        'content' => 'Link to deal',
                    ],
                ])
            ->andReturn($response);

        $client->shouldReceive('request')
            ->once()
            ->with('POST', 'https://api.pipedrive.com/v1/activities?api_token=PipedriveToken',
                [
                    'form_params' => [
                        'subject'  => 'Buy milk',
                        'type'     => 'shopping',
                        'due_date' => '2017-12-18',
                    ],
                ])
            ->andReturn($response);

        $client->shouldReceive('request')
            ->once()
            ->with('POST', 'https://api.pipedrive.com/v1/notes?api_token=PipedriveToken',
                [
                    'form_params' => [
                        'deal_id'  => 1,
                        'content'  => 'Customer bought milk',
                    ],
                ])
            ->andReturn($response);

        $channel = new PipedriveChannel($client);
        $channel->send(new TestNotifiable(), new CreateDealWithActivitiesNotification());
    }

    /**
     * @test
     */
    public function it_throws_an_exception_when_no_token_is_provided()
    {
        $this->setExpectedException(InvalidConfiguration::class);

        $client = Mockery::mock(Client::class);

        $channel = new PipedriveChannel($client);
        $channel->send(new TestNotifiableWithoutPipedriveToken(), new CreateDealWithActivitiesNotification());
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_no_to_pipedrive_method_is_provided()
    {
        $this->setExpectedException(InvalidConfiguration::class);

        $resource = new TestResource();
        $client = Mockery::mock(Client::class);

        $channel = new PipedriveChannel($client);
        $channel->send(new TestNotifiable(), new NotificationWithoutToPipedriveMethod());
    }
}

class TestNotifiable
{
    use \Illuminate\Notifications\Notifiable;

    public function routeNotificationForPipedrive()
    {
        return 'PipedriveToken';
    }
}

class TestNotifiableWithoutPipedriveToken
{
    use \Illuminate\Notifications\Notifiable;

    public function routeNotificationForPipedrive()
    {
    }
}

class NotificationWithoutToPipedriveMethod extends Notification
{
}

class CreateDealWithActivitiesNotification extends Notification
{
    public function toPipedrive($notifiable)
    {
        return
            (new PipedriveMessage())
                ->deal(function ($deal) {
                    $deal->stage(1)
                         ->title('new deal')
                         ->activity(function ($activity) {
                             $activity->subject('Call Jane')
                                      ->type('call');
                         })
                         ->activity(function ($activity) {
                             $activity->id(3)
                                      ->subject('Email Joe')
                                      ->type('mail');
                         })
                         ->note(function ($note) {
                             $note->content('Link to deal');
                         });
                })
                ->activity(function ($activity) {
                    $activity->subject('Buy milk')
                             ->type('shopping')
                             ->due('2017-12-18');
                })
                ->note(function ($note) {
                    $note->deal(1)
                         ->content('Customer bought milk');
                });
    }
}
