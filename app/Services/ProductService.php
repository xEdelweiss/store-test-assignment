<?php

namespace App\Services;

use App\DTOs\Product\ProductFilterDto;
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
}
