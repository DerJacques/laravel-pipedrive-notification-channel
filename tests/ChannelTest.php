<?php
namespace DerJacques\PipedriveNotifications\Test;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Notifications\Notification;
use Mockery;
use DerJacques\PipedriveNotifications\PipedriveChannel;
use DerJacques\PipedriveNotifications\PipedriveMessage;
use PHPUnit\Framework\TestCase;

class ChannelTest extends TestCase
{
    /** @test */
    public function it_can_create_deals_based_on_a_notification()
    {
        $response = new Response(200, ['Content-Type' => 'application/json'], '{"data" : { "id" : 1 }}');
        $client = Mockery::mock(Client::class);
        $client->shouldReceive('request')
            ->once()
            ->with('POST', 'https://api.pipedrive.com/v1/deals?api_token=PipedriveToken',
                [
                    'form_params' => [
                        'stage_id' => 1,
                        'title' => 'new deal'
                    ],
                ])
            ->andReturn($response);
        $channel = new PipedriveChannel($client);
        $channel->send(new TestNotifiable(), new NewDealNotification());
    }

    /** @test */
    public function it_can_update_deals_based_on_a_notification()
    {
        $response = new Response(200, ['Content-Type' => 'application/json'], '{"data" : { "id" : 1 }}');
        $client = Mockery::mock(Client::class);
        $client->shouldReceive('request')
            ->once()
            ->with('PUT', 'https://api.pipedrive.com/v1/deals/1?api_token=PipedriveToken',
                [
                    'form_params' => [
                        'title' => 'updated title'
                    ]
                ])
            ->andReturn($response);
        $channel = new PipedriveChannel($client);
        $channel->send(new TestNotifiable(), new UpdatedDealNotification());
    }

    /**
     * @test
    */
    public function it_can_create_deals_with_activities()
    {
        $response = new Response(200, ['Content-Type' => 'application/json'], '{"data" : { "id" : 1 }}');
        $client = Mockery::mock(Client::class);

        $client->shouldReceive('request')
            ->once()
            ->with('POST', 'https://api.pipedrive.com/v1/deals?api_token=PipedriveToken',
                [
                    'form_params' => [
                        'stage_id' => 1,
                        'title' => 'new deal'
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
                        'type' => 'call'
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
                        'type' => 'mail'
                    ],
                ])
            ->andReturn($response);

        $channel = new PipedriveChannel($client);
        $channel->send(new TestNotifiable(), new CreateDealWithActivitiesNotification());
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

class NewDealNotification extends Notification
{
    public function toPipedrive($notifiable)
    {
        return
            (new PipedriveMessage())
                ->deal(function ($deal) {
                    $deal->stage(1)
                         ->title('new deal');
                });
    }
}

class UpdatedDealNotification extends Notification
{
    public function toPipedrive($notifiable)
    {
        return
            (new PipedriveMessage())
                ->deal(function ($deal) {
                    $deal->id(1)
                         ->title('updated title');
                });
    }
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
                         });
                });
    }
}
