<?php

namespace App\DTOs\Order;

use App\Services\Order\OrderStatus;

readonly class OrderFilterDto
{
    public function __construct(
        public ?int $userId = null,
        public ?OrderStatus $status = null,
    ) {}
}
