<?php

namespace Cappuc\RestCountries;

use Http\Client\HttpClient;
use Http\Message\RequestFactory;
use Tightenco\Collect\Support\Collection;

class RestCountriesApi implements CountryApi
{
    /**
     * @var HttpClient
     */
    protected $client;

    /**
     * @var string
     */
    protected $baseUrl = 'https://restcountries.eu/rest/v2/';
    /**
     * @var RequestFactory
     */
    protected $requestFactory;

    public function __construct(HttpClient $client, RequestFactory $requestFactory)
    {
        $this->client = $client;
        $this->requestFactory = $requestFactory;
    }

    /**
     * @param       $url
     * @param array $fields
     * @return Collection
     * @throws \Http\Client\Exception
     */
    protected function request($url, $fields = [])
    {
        $params = count($fields) > 0 ? [
            'fields' => implode(';', $fields),
        ] : [];

        $response = $this->client->sendRequest(
            $this->requestFactory->createRequest('GET', $this->baseUrl . $url . '?' . http_build_query($params))
        )->getBody()->getContents();

        return $this->formatResponse($response);
    }

    /**
     * @param string $jsonResponse
     * @return Collection
     */
    protected function formatResponse(string $jsonResponse): Collection
    {
        return (new Collection(json_decode($jsonResponse, true)))->mapInto(Country::class);
    }

    /**
     * @param array $fields
     * @return Collection
     * @throws \Exception
     * @throws \Http\Client\Exception
     */
    public
    function all($fields = []): Collection
    {
        return $this->request('all', $fields);
    }

    /**
     * @param string $callingCode
     * @param array  $fields
     * @return Collection
     * @throws \Exception
     * @throws \Http\Client\Exception
     */
    public function findByCallingCode($callingCode, $fields = []): Collection
    {
        return $this->request("callingcode/{$callingCode}", $fields);
    }

    public function findByCurrency($currency, $fields = []): Collection
    {
        // TODO: Implement findByCurrency() method.
    }

    public function findByLanguage($language, $fields = []): Collection
    {
        // TODO: Implement findByLanguage() method.
    }

    public function findByCodes($codes, $fields = []): Collection
    {
        // TODO: Implement findByCodes() method.
    }

    public function findByName($name, $fields = []): Collection
    {
        // TODO: Implement findByName() method.
    }
}