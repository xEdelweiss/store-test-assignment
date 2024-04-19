<?php

namespace App\DTOs\Order;

readonly class CartItemDto
{
    public function __construct(
        public int $productId,
        public int $quantity
    ) {}
}
