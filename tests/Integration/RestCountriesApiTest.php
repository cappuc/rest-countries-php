<?php

namespace Cappuc\RestCountries\Tests\Integration;

use Cappuc\RestCountries\Country;
use Cappuc\RestCountries\RestCountriesApi;
use Http\Adapter\Guzzle6\Client;
use Http\Message\MessageFactory\GuzzleMessageFactory;
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
        $response = $this->api->all(['name', 'alpha2Code', 'alpha3Code', 'callingCodes', 'translations']);

        $response->each(function (Country $country) {
            $this->assertCount(5, $country->toArray());
            $this->assertArrayHasKey('name', $country->toArray());
            $this->assertArrayHasKey('alpha2Code', $country->toArray());
            $this->assertArrayHasKey('alpha3Code', $country->toArray());
            $this->assertArrayHasKey('callingCodes', $country->toArray());
            $this->assertArrayHasKey('translations', $country->toArray());
        });
    }
}
