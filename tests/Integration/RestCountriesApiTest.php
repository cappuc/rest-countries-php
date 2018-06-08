<?php

namespace Cappuc\RestCountries\Tests\Integration;

use Http\Adapter\Guzzle6\Client;
use Http\Message\MessageFactory\GuzzleMessageFactory;
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
    public function it_can_fetch_all_countries()
    {
        $results = $this->api->all();

        $this->assertContainsOnlyInstancesOf(Country::class, $results);
    }

    /** @test */
    public function it_can_filter_results()
    {
        $response = $this->api->all(['name', 'alpha2Code']);

        $response->each(function(Country $country) {
            $this->assertCount(2, $country->toArray());
            $this->assertArrayHasKey('name', $country->toArray());
            $this->assertArrayHasKey('alpha2Code', $country->toArray());
        });
    }

    /** @test */
    public function it_can_find_countries_by_calling_code()
    {
        $response = $this->api->findByCallingCode('39');

        $this->assertCount(1, $response);
        $this->assertContainsOnlyInstancesOf(Country::class, $response);
        $this->assertEquals('Italy', $response->first()->name);
    }
}
