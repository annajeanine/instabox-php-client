<?php
// Autoload composer
require '../../vendor/autoload.php';

$instabox = new \Instabox\Instabox(new \Instabox\Connection('clientID', 'clientSecret', true));

// Generate random ParcelId. For requirements, see instadocs
$parcelId = 'CustomerNumber' . random_int(0, 90000000);

// Create the order
$order = $instabox->order();

// Set properties of order
$order->availability_token = 'e355e286-f11d-451d-8e65-ca295311a62c';
$order->parcel_id = $parcelId;
$order->order_number = 'P2022-7849';
$order->service_type = 'EXPRESS';

// Create nested Recipient model and add this to the order
$recipient = new \Instabox\Models\Order\NestedModels\Recipient();
$recipient->name = 'Anna Jeanine';
$recipient->street = 'Testvagen 1';
$recipient->zip = '12345';
$recipient->city = 'Testtown';
$recipient->country_code = 'SE';
$recipient->email_address = 'email@domain.com';
$recipient->mobile_phone_number = '0701234567';
$order->recipient = $recipient;

// Create nested Sender model and add this to the order
$sender = new \Instabox\Models\Order\NestedModels\Sender();
$sender->name = 'Instabox AB';
$sender->street = 'test';
$sender->street2 = 'Port 12';
$sender->zip = '17236';
$sender->city = 'Bromma';
$sender->country_code = 'SE';
$sender->warehouse_id = 'SE-Stockholm';
$order->sender = $sender;

// Create nested DeliveryOption model and add this to the order
$deliveryOptions = new \Instabox\Models\Order\NestedModels\DeliveryOption();
$deliveryOptions->sort_code = 'IN20';
$order->delivery_option = $deliveryOptions;

try {
    $order->save();
} catch (\Instabox\Exceptions\UnauthorizedException $exception) {
    // Client credentials are incorrect
} catch (\Instabox\Exceptions\ServerException $exception) {
    // Got a [500, 502, 503, 503, 507] response
} catch (\Instabox\Exceptions\ResponseException $exception) {
    // Response was unexpected.
} catch (\GuzzleHttp\Exception\ConnectException $exception) {
    // Guzzle ConnectionException occured
} catch (Exception $exceptions) {
    // Unexpected error occured
}

$label = $instabox->label($order);
$generatedLabel = $label->generateLabel();

file_put_contents('example-label.pdf', $generatedLabel);
