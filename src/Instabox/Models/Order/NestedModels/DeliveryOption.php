<?php

namespace Instabox\Models\Order\NestedModels;

use Instabox\Models\BaseNestedModel;

class DeliveryOption extends BaseNestedModel
{
    public array $fillable = [
        'sort_code',
        'leave_by_door',
        'door_code',
        'delivery_instructions',
        'notify_by'
    ];
}
