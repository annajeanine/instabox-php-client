<?php

namespace Instabox\Models;

class BaseNestedModel
{
    public array $attributes = [];
    public array $fillable = [];

    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }

    protected function fill(array $attributes)
    {
        foreach ($this->fillableFromArray($attributes) as $key => $value) {
            if ($this->isFillable($key)) {
                $this->setAttribute($key, $value);
            }
        }
    }

    protected function fillableFromArray(array $attributes): array
    {
        if (count($this->fillable) > 0) {
            return array_intersect_key($attributes, array_flip($this->fillable));
        }

        return $attributes;
    }

    protected function isFillable($key): bool
    {
        return in_array($key, $this->fillable);
    }

    protected function setAttribute($key, $value): void
    {
        $this->attributes[$key] = $value;
    }

    public function __get($key)
    {
        if (isset($this->attributes[$key])) {
            return $this->attributes[$key];
        }

        return null;
    }

    public function __isset($key)
    {
        return isset($this->attributes[$key]);
    }

    public function __set($key, $value)
    {
        if ($this->isFillable($key)) {
            $this->setAttribute($key, $value);
        }
    }
}
