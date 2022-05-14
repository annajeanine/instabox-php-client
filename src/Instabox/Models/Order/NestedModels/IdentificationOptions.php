<?php

namespace Instabox\Models\Order\NestedModels;

use Instabox\Models\BaseNestedModel;

class IdentificationOptions extends BaseNestedModel
{
    public array $fillable = [
        'type',
        'minimum_age',
        'name',
        'national_identification_number',
        'verify_person_using',
    ];
}
