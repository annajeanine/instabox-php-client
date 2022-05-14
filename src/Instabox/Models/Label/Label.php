<?php

namespace Instabox\Models\Label;

use Dompdf\Dompdf;
use Instabox\Models\Order\Order;

class Label
{
    protected Order $order;
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function generateLabel()
    {
        $html = new LabelHtmlGenerator($this->order);

        $dompdf = new Dompdf();
        $dompdf->setPaper('a6');
        $dompdf->loadHtml($html->getHtml());
        $dompdf->render();

        return $dompdf->output();
    }
}
