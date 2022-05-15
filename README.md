# Instabox API PHP Client

Unofficial PHP API Client for the Instabox API. More information about Instabox can be found on https://instabox.io/. 

Full documentation about the Instabox API can be found on https://www.instadocs.se. Contact your account manager for credentials. 

### Installation
The client can be easily installed through composer: 

```
composer require annajeanine/instabox-php-client
```

### Connection
Initialize the Connection class with your Client ID and Client Secret. Optionally for development purposes, set the Sandbox variable to true. 

```
$connection = new \Instabox\Connection('clientID', 'clientSecret', true);
```

The connection is required for the Instabox interface which handles which models can be initialized.
```
$instabox = new \Instabox\Instabox($connection);
```

### Create an order instance
To create an order, use the Instabox class. Initialize an order through here. 

```
$order = $instabox->order();
```

Set all required properties of the order. For the required properties, consult the API documentation. 

The order model has nested properties, such as the `Recipient` and `Sender`. Initialize these and set these to the corresponding property of the `Order` model. 

```
// Create nested DeliveryOption model and add this to the order
$deliveryOptions = new \Instabox\Models\Order\NestedModels\DeliveryOption();
$deliveryOptions->sort_code = 'IN20';
$order->delivery_option = $deliveryOptions;
```

### Create order through API
To create the order through the API, you have to save the `Order` model. 

```
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
```

Note; There are a couple of exceptions which could be thrown. Working on improving these once response examples become available. 

### Generate a label
Instabox provides support to create a label through their API. But, as stated in their documentation

> Please avoid to use this if you can create labels on your own. This function is designed for really small merchants. Note that an order needs to be created first and then the label can be fetched. So it can take a few minutes before the label is available.

Therefore, this client provides generating a label through the usage of `dompdf`. Therefore, there is no need to wait and a label can be generated directly after making the order. To generate a label for a order use:

```
$label = $instabox->label($order);
$generatedLabel = $label->generateLabel();

file_put_contents('example-label.pdf', $generatedLabel);
```

### Credits
The setup of the API is inspired by the set-up of Picqer PHP clients:
- https://github.com/picqer/sendcloud-php-client
- https://github.com/picqer/moneybird-php-client


### To do's 
- Improve label
  - Add small Instabox logo to bottom of label
  - Make label pixel perfect
  - Find out which phone number of recipient should be used. 
- Improve unhappy path with response examples 
- Add tests
