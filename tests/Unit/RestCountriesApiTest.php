<?php

namespace Cappuc\RestCountries\Tests\Unit;

use Http\Message\MessageFactory\GuzzleMessageFactory;
use Http\Mock\Client;
use Cappuc\RestCountries\Country;
use Cappuc\RestCountries\RestCountriesApi;
use PHPUnit\Framework\TestCase;

class RestCountriesApiTest extends TestCase
{
    /**
     * @var RestCountriesApi
     */
    private $api;
    /**
     * @var Client
     */
    private $client;

    protected function setUp()
    {
        parent::setUp();

        $this->client = new Client();
        $this->api = new RestCountriesApi($this->client, new GuzzleMessageFactory());
    }

    /** @test */
    public function it_can_fetch_all_countries__fake()
    {
        $this->api->all();

        $requests = $this->client->getRequests();

        $this->assertCount(1, $requests);
        $this->assertEquals('GET', $requests[0]->getMethod());
        $this->assertEquals('restcountries.eu', $requests[0]->getUri()->getHost());
        $this->assertEquals('/rest/v2/all', $requests[0]->getUri()->getPath());
        $this->assertEquals('', $requests[0]->getUri()->getQuery());
    }

    /** @test */
    public function it_can_fetch_all_countries()
    {
        $results = $this->api->all();

        $this->assertContainsOnlyInstancesOf(Country::class, $results);
    }

    /** @test */
    public function it_can_filter_results()
    {
        $this->api->all(['name', 'alpha2Code']);

        $requests = $this->client->getRequests();

        $this->assertCount(1, $requests);
        $this->assertEquals('GET', $requests[0]->getMethod());
        $this->assertEquals('restcountries.eu', $requests[0]->getUri()->getHost());
        $this->assertEquals('/rest/v2/all', $requests[0]->getUri()->getPath());
        $this->assertEquals('fields=name;alpha2Code', urldecode($requests[0]->getUri()->getQuery()));
    }

    /** @test */
    public function it_can_find_countries_by_calling_code()
    {
        $this->api->findByCallingCode('39');

        $requests = $this->client->getRequests();

        $this->assertCount(1, $requests);
        $this->assertEquals('GET', $requests[0]->getMethod());
        $this->assertEquals('restcountries.eu', $requests[0]->getUri()->getHost());
        $this->assertEquals('/rest/v2/callingcode/39', $requests[0]->getUri()->getPath());
        $this->assertEquals('', $requests[0]->getUri()->getQuery());
    }
}
