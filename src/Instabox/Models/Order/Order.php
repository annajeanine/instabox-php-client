<?php

namespace Instabox\Models\Order;

use Instabox\Models\BaseRequestModel;
use Instabox\Models\Order\NestedModels\DeliveryOption;
use Instabox\Models\Order\NestedModels\Details;
use Instabox\Models\Order\NestedModels\IdentificationOptions;
use Instabox\Models\Order\NestedModels\Recipient;
use Instabox\Models\Order\NestedModels\Sender;
use Instabox\Models\Traits\Storable;

class Order extends BaseRequestModel
{
    use Storable;

    protected array $fillable = [
        'availability_token',
        'parcel_id',
        'order_number',
        'recipient',
        'sender',
        'delivery_option',
        'details',
        'identification_options',
        'service_type'
    ];

    protected array $multipleNestedEntities = [
        'recipient' => [
            'entity' => Recipient::class,
        ],
        'sender' => [
            'entity' => Sender::class,
        ],
        'delivery_option' => [
            'entity' => DeliveryOption::class,
        ],
        'details' => [
            'entity' => Details::class,
        ],
        'identification_options' => [
            'entity' => IdentificationOptions::class
        ],
    ];

    protected string $url = 'https://webshopintegrations.instabox.se/v2/orders';
    protected string $urlSandbox = 'https://webshopintegrations-sandbox.instabox.se/v2/orders';

    public function getLastThreeDigitsOfParcelId(): string
    {
        return substr($this->parcel_id, -3);
    }
}
