<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\ProductsFilterRequest;
use App\Http\Resources\Product\ProductResource;
use App\Models\Product;
use App\Services\ProductService;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService
    ) {}

    public function index(ProductsFilterRequest $request)
    {
        $items = $this->productService->getProducts($request->toDto());

        return ProductResource::collection($items);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }
}
