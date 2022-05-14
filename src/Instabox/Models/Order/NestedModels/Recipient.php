<?php

namespace Instabox\Models\Order\NestedModels;

use Instabox\Models\BaseNestedModel;

class Recipient extends BaseNestedModel
{
    public array $fillable = [
        'name',
        'street',
        'zip',
        'city',
        'country_code',
        'mobile_phone_number',
        'home_phone_number',
        'work_phone_number',
        'email_address',
    ];

    public function getPhoneNumber() {
        // TODO: The label displays only one phone number, but in what priority should these be displayed?
    }
}
