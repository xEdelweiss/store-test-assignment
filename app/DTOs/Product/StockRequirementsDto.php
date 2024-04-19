<?php

namespace App\DTOs\Product;

readonly class StockRequirementsDto
{
    public function __construct(
        /** @var ProductInStockDto[] */
        public array $items
    ) {}

    public function toQuantityById(): array
    {
        return array_reduce(
            $this->items,
            static fn($previous, $item) => $previous + [$item->productId => $item->quantity],
            [],
        );
    }
}
