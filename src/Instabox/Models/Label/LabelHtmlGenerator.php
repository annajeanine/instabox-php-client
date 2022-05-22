<?php

namespace Instabox\Models\Label;

use Instabox\Models\Order\NestedModels\Recipient;
use Instabox\Models\Order\NestedModels\Sender;
use Instabox\Models\Order\Order;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class LabelHtmlGenerator
{
    protected Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }
    public function getHtml(): string
    {
        $order = $this->order;
        $lastThreeDigitsParcelId = $this->order->getLastThreeDigitsOfParcelId();
        $generator = new BarcodeGeneratorPNG();
        $barcode = base64_encode($generator->getBarcode($this->order->parcel_id, $generator::TYPE_CODE_128));

        $loader = new FilesystemLoader(__DIR__ . '/templates');
        $twig = new Environment($loader);

        return $twig->render('label.html', [
            'order' => $order,
            'lastThreeDigitsParcelId' => $lastThreeDigitsParcelId,
            'barcode' => $barcode,
        ]);
    }

}
