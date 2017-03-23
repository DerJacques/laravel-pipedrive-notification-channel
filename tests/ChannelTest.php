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
        $response = new Response(200);
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
        $response = new Response(200);
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
                ->stage(1)
                ->title('new deal');
    }
}

class UpdatedDealNotification extends Notification
{
    public function toPipedrive($notifiable)
    {
        return
            (new PipedriveMessage())
                ->deal(1)
                ->title('updated title');
    }
}
