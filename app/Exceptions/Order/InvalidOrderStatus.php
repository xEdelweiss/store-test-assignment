<?php

namespace App\Exceptions\Order;

use App\Models\Order;

class InvalidOrderStatus extends \LogicException
{
    public function __construct(Order $order)
    {
        parent::__construct("Order {$order->id} has invalid status {$order->status}");
    }
}
