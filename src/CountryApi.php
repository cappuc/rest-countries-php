<?php

namespace Cappuc\RestCountries;

use Tightenco\Collect\Support\Collection;

interface CountryApi
{
    public function all($fields = []): Collection;

    public function findByCallingCode($callingCode, $fields = []): Collection;

    public function findByCurrency($currency, $fields = []): Collection;

    public function findByLanguage($language, $fields = []): Collection;

    public function findByCodes($codes, $fields = []): Collection;

    public function findByName($name, $fields = []): Collection;
}