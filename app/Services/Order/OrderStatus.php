<?php

namespace App\Services\Order;

enum OrderStatus: string
{
    case Created = 'created';
    case Shipped = 'shipped';
    case Delivered = 'delivered';
}
