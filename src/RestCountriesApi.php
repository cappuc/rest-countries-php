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
     * @return array
     * @throws \Exception
     */
    protected function request($url, $fields = [])
    {
        $params = count($fields) > 0 ? [
            'fields' => implode(';', $fields),
        ] : [];

        try {
            $response = $this->client->sendRequest(
                $this->requestFactory->createRequest('GET', $this->baseUrl . $url . '?' . http_build_query($params))
            )->getBody()->getContents();

            return json_decode($response, true);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        } catch (\Http\Client\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param array $fields
     * @return Collection
     * @throws \Exception
     */
    public function all($fields = []): Collection
    {
        return (new Collection($this->request('all', $fields)))->mapInto(Country::class);
    }

    /**
     * @param string $callingCode
     * @param array  $fields
     * @return Collection
     * @throws \Exception
     */
    public function findByCallingCode($callingCode, $fields = []): Collection
    {
        return (new Collection($this->request("callingcode/{$callingCode}", $fields)))->mapInto(Country::class);
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