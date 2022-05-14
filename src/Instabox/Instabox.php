<?php

namespace Instabox;

use Instabox\Models\Label\Label;
use Instabox\Models\Order\Order;

class Instabox
{
    protected Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function order(): Order
    {
        return new Order($this->connection);
    }

    public function label(Order $order): Label
    {
        return new Label($order);
    }
}
