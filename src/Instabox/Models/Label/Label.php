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

        // For testing purposes set pdf to local file
        // file_put_contents('test.pdf', $dompdf->output());
    }
}
