<?php

namespace App\Exceptions\Product;

use App\DTOs\Product\ProductInStockDto;

class NotEnoughInStockException extends \RuntimeException
{
    /** @var ProductInStockDto[] */
    private array $mismatchItems;

    /** @param ProductInStockDto[] $mismatchItems */
    public function __construct($message = 'Not enough in stock', array $mismatchItems = [])
    {
        parent::__construct($message);
        $this->mismatchItems = $mismatchItems;
    }

    public function getMismatchItems(): array
    {
        return $this->mismatchItems;
    }
}
