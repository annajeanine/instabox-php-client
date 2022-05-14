<?php

namespace Instabox\Models\Traits;

trait Storable
{
    public function save()
    {
        return $this->connection()->rawRequest('POST', $this->getUrl(), $this->json());
    }
}
