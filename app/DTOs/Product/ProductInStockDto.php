<?php

namespace App\DTOs\Product;

readonly class ProductInStockDto
{
    public function __construct(
        public int $productId,
        public int $quantity
    ) {}
}
