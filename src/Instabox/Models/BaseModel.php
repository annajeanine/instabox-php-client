<?php

namespace Instabox\Models;

use Instabox\Connection;

class BaseModel
{
    protected Connection $connection;

    protected array $attributes = [];
    protected array $fillable = [];
    protected array $multipleNestedEntities = [];

    protected string $url = '';
    protected string $urlSandbox = '';

    public function __construct(Connection $connection, array $attributes = [])
    {
        $this->connection = $connection;
        $this->fill($attributes);
    }

    public function connection()
    {
        return $this->connection;
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

    public function getUrl(): string
    {
        if ($this->connection->isSandbox()) {
            return $this->urlSandbox;
        }

        return $this->url;
    }

    public function json(): string
    {
        $array = $this->getArrayWithNestedObjects();

        return json_encode($array, JSON_FORCE_OBJECT);
    }

    private function getArrayWithNestedObjects(): array
    {
        $entityName = $this->getEntityName();
        $result[$entityName] = [];
        $multipleNestedEntities = $this->getMultipleNestedEntities();

        foreach ($this->attributes as $attributeName => $attributeValue) {
            if (! is_object($attributeValue)) {
                $result[$entityName][$attributeName] = $attributeValue;
            }

            if (array_key_exists($attributeName, $multipleNestedEntities)) {
                $result[$entityName][$attributeName] = $attributeValue->attributes;
            }
        }

        return $result;
    }

    protected function getEntityName(): string
    {
        return strtolower((new \ReflectionClass($this))->getShortName());
    }

    public function getMultipleNestedEntities(): array
    {
        return $this->multipleNestedEntities;
    }
}
