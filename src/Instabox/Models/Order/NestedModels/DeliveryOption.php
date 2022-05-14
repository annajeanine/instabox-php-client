<?php

namespace Instabox\Models\Order\NestedModels;

use Instabox\Models\BaseNestedModel;

class DeliveryOption extends BaseNestedModel
{
    public array $fillable = [
        'sort_code',
    ];
}
