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
    protected $baseUrl = 'https://restcountries.com/v3/';

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
     * @throws \Http\Client\Exception
     * @return Collection
     */
    protected function request($url, $fields = [])
    {
        $params = count($fields) > 0 ? [
            'fields' => implode(',', $this->prepareFieldsForRequest($fields)),
        ] : [];

        $response = $this->client->sendRequest(
            $this->requestFactory->createRequest('GET', $this->baseUrl.$url.'?'.http_build_query($params))
        )->getBody()->getContents();

        return $this->formatResponse($response);
    }

    /**
     * @param string $jsonResponse
     * @return Collection
     */
    protected function formatResponse(string $jsonResponse): Collection
    {
        return Collection::make(json_decode($jsonResponse, true))
            ->map(function ($fields) {
                return $this->normalizeItemResponse($fields);
            })
            ->mapInto(Country::class);
    }

    /**
     * @param array $fields
     * @throws \Exception
     * @throws \Http\Client\Exception
     * @return Collection
     */
    public function all($fields = []): Collection
    {
        return $this->request('all', $fields);
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

    private function prepareFieldsForRequest(array $fields)
    {
        $v2Tov3Mapping = [
            'alpha2Code' => 'cca2',
            'alpha3Code' => 'cca3',
            'callingCodes' => 'idd',
        ];

        return collect($fields)->map(function ($field) use ($v2Tov3Mapping) {
            if (isset($v2Tov3Mapping[$field])) {
                return $v2Tov3Mapping[$field];
            }
            return $field;
        })->all();
    }

    private function normalizeItemResponse(array $fields)
    {
        $v3Tov2Mapping = [
            'cca2' => 'alpha2Code',
            'cca3' => 'alpha3Code',
        ];

        return collect($fields)->mapWithKeys(function ($value, $key) use ($v3Tov2Mapping) {
            if (array_key_exists($key, $v3Tov2Mapping)) {
                return [$v3Tov2Mapping[$key] => $value];
            }

            if ($key === 'name') {
                return [$key => $value['common']];
            }

            if ($key === 'translations') {
                return [
                    $key => [
                        'it' => $value['ita']['common'],
                        'fr' => $value['fra']['common'],
                        'de' => $value['deu']['common'],
                        'es' => $value['est']['common'],
                    ],
                ];
            }

            if ($key === 'idd') {
                return [
                    'callingCodes' => array_map(function ($suffix) use ($value) {
                        return ltrim($value['root'], '+').$suffix;
                    }, $value['suffixes'] ?? []),
                ];
            }

            return [$key => $value];
        })->all();
    }
}