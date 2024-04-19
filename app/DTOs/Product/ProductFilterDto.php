<?php

namespace App\DTOs\Product;

readonly class ProductFilterDto
{
    public function __construct(
        public ?float $maxPrice = null,
        public ?float $minPrice = null,
        public ?string $title = null
    ) {}
}
