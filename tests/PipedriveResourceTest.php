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
use DerJacques\PipedriveNotifications\Exceptions\FailedRequest;
use DerJacques\PipedriveNotifications\PipedriveResource;

class PipedriveResourceTest extends TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * @test
     */
    public function it_can_be_assigned_an_id()
    {
        $resource = new PipedriveResource();
        $resource->id(5);
        $this->assertEquals(5, $resource->getId());
    }

    /**
     * @test
     */
    public function it_can_return_return_the_plural_version_of_a_resource()
    {
        $resourceWithoutPluralAttribute = new PipedriveResource();
        $this->assertEquals('pipedriveresources', $resourceWithoutPluralAttribute->getPluralName());

        $resourceWithPluralAttribute = new TestResource();
        $this->assertEquals('tests', $resourceWithPluralAttribute->getPluralName());
    }

    /**
     * @test
     */
    public function it_can_return_return_the_singular_version_of_a_resource()
    {
        $resourceWithoutPluralAttribute = new PipedriveResource();
        $this->assertEquals('pipedriveresource', $resourceWithoutPluralAttribute->getSingularName());

        $resourceWithPluralAttribute = new TestResource();
        $this->assertEquals('test', $resourceWithPluralAttribute->getSingularName());
    }

    /**
     * @test
     */
    public function it_accepts_a_request_client_and_token()
    {
        $resource = new PipedriveResource();
        $client = Mockery::mock(Client::class);

        $resource->setClient($client, 'PipedriveToken');

        $this->assertEquals($client, $resource->getClient());
        $this->assertEquals('PipedriveToken', $resource->getToken());
    }

    /**
     * @test
     */
    public function it_can_save_new_resources()
    {
        $resource = new PipedriveResource();
        $client = Mockery::mock(Client::class);
        $response = new Response(200, ['Content-Type' => 'application/json'], '{"data" : { "id" : 1 }}');

        $client->shouldReceive('request')
            ->once()
            ->with('POST', Mockery::any(), Mockery::any())
            ->andReturn($response);

        $resource->setClient($client, 'PipedriveToken');

        $this->assertEquals(200, $resource->save()->getStatusCode());
    }

    /**
     * @test
     */
    public function it_can_update_existing_resources()
    {
        $resource = new PipedriveResource();
        $resource->id(3);
        $client = Mockery::mock(Client::class);
        $response = new Response(200, ['Content-Type' => 'application/json'], '{"data" : { "id" : 1 }}');

        $client->shouldReceive('request')
            ->once()
            ->with('PUT', Mockery::any(), Mockery::any())
            ->andReturn($response);

        $resource->setClient($client, 'PipedriveToken');

        $this->assertEquals(200, $resource->save()->getStatusCode());
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_no_client_or_token_is_provided()
    {
        $this->setExpectedException(InvalidConfiguration::class);

        $resource = new PipedriveResource();
        $resource->save();
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_pipedrive_returns_an_error()
    {
        $this->setExpectedException(FailedRequest::class);

        $resource = new PipedriveResource();
        $client = Mockery::mock(Client::class);
        $response = new Response(404, ['Content-Type' => 'application/json'], '{"error" : "not found"}');

        $client->shouldReceive('request')
            ->once()
            ->andReturn($response);

        $resource->setClient($client, 'PipedriveToken');
        $resource->save();
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_a_required_attribute_is_not_provided()
    {
        $this->setExpectedException(FailedRequest::class);

        $resource = new TestResource();
        $client = Mockery::mock(Client::class);

        $resource->setClient($client, 'PipedriveToken');
        $resource->save();
    }

    /**
     * @test
     */
    public function it_can_convert_attributes_to_a_pipedrive_friendly_array()
    {
        $resource = new PipedriveResource();
        $this->assertTrue(is_array($resource->toPipedriveArray()));
    }

}

class TestResource extends PipedriveResource
{
    protected $plural = 'tests';
    protected $singular = 'test';
    protected $required = ['title'];
}
