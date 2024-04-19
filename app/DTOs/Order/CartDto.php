<?php

namespace App\DTOs\Order;

readonly class CartDto
{
    public function __construct(
        /** @var array<CartItemDto> */
        public readonly array $items
    ) {}
}
