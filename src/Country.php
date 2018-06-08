<?php

namespace Cappuc\RestCountries;

use Tightenco\Collect\Contracts\Support\Arrayable;
use Tightenco\Collect\Contracts\Support\Jsonable;
use Tightenco\Collect\Support\Collection;

class Country implements Arrayable, \JsonSerializable, Jsonable
{
    /**
     * @var Collection
     */
    protected $attributes;

    public function __construct($attributes = [])
    {
        $this->attributes = new Collection($attributes);
    }

    public function getName($locale = null)
    {
        if (is_null($locale)) {
            return $this->name;
        }

        if (array_key_exists($locale, $this->translations)) {
            return $this->translations[$locale];
        }

        return $this->name;
    }

    public function __get($name)
    {
        return $this->attributes->get($name);
    }

    public function __set($name, $value)
    {
        $this->attributes->put($name, $value);
    }

    public function toArray():array
    {
        return $this->attributes->toArray();
    }

    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    public function jsonSerialize()
    {
        return $this->toJson();
    }
}