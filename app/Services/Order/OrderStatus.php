<?php

namespace App\Services\Order;

enum OrderStatus: string
{
    case Created = 'created';
    case Processing = 'processing';
    case Shipped = 'shipped';
    case Delivered = 'delivered';
}
