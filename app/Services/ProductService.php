<?php

namespace App\Services;

use App\DTOs\Product\StockRequirementsDto;
use App\DTOs\Product\ProductInStockDto;
use App\DTOs\Product\ProductFilterDto;
use App\Exceptions\Product\NotEnoughInStockException;
use App\Models\Product;
use Illuminate\Pagination\AbstractPaginator;

class ProductService
{
    private int $itemsPerPage;

    public function __construct(int $itemsPerPage = null)
    {
        $this->itemsPerPage = $itemsPerPage ?? config('services.product.items_per_page');
    }

    public function getProducts(ProductFilterDto $filter): AbstractPaginator
    {
        return Product::query()
            ->when($filter->minPrice, fn($query, $minPrice) => $query->minPrice($minPrice))
            ->when($filter->maxPrice, fn($query, $maxPrice) => $query->maxPrice($maxPrice))
            ->when($filter->title, fn($query, $title) => $query->titleContains($title))
            ->paginate($this->itemsPerPage);
    }

    /** @throws NotEnoughInStockException */
    public function checkStockQuantity(StockRequirementsDto $requirements): true
    {
        $requiredQuantityById = $requirements->toQuantityById();

        $availableQuantityById = Product::whereIn('id', array_keys($requiredQuantityById))
            ->get()
            ->pluck('quantity', 'id')
            ->toArray();

        $mismatchItems = [];

        foreach ($requiredQuantityById as $productId => $requiredQuantity) {
            $availableQuantity = $availableQuantityById[$productId] ?? 0;

            if ($availableQuantity < $requiredQuantity) {
                $mismatchItems[] = new ProductInStockDto($productId, $availableQuantity);
            }
        }

        if (!empty($mismatchItems)) {
            throw new NotEnoughInStockException(mismatchItems: $mismatchItems);
        }

        return true;
    }
}
