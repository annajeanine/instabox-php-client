<?php

namespace Instabox\Models\Label;

use Dompdf\Dompdf;
use Dompdf\Options;
use Instabox\Models\Order\Order;
use Picqer\Barcode\BarcodeGeneratorPNG;

class Label
{
    protected Order $order;
    public function __construct(Order $order)
    {
    }

    public function generateLabel()
    {
        // TODO: Generate label
    }

    protected function generateBarcodeImage(): string
    {
        $generator = new BarcodeGeneratorPNG();
        return '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode($this->order->parcel_id, $generator::TYPE_CODE_128)) . '">';
    }
}
