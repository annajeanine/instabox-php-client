<?php

namespace Instabox\Models\Order\NestedModels;

use Instabox\Models\BaseNestedModel;

class Sender extends BaseNestedModel
{
    public array $fillable = [
        'name',
        'street',
        'street2',
        'zip',
        'city',
        'country_code',
        'warehouse_id',
    ];
}
