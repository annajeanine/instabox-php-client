<?php

namespace Instabox\Models\Order\NestedModels;

use Instabox\Models\BaseNestedModel;

class Details extends BaseNestedModel
{
    public array $fillable = [
        'total_weight',
        'total_value',
    ];
}
