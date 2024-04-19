<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_index(): void
    {
        $this->seed();

        $response = $this->get('/api/products');

        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');
    }

    public function test_index_with_max_price_filter(): void
    {
        Product::factory(2, [
            'price' => 100
        ])->create();
        Product::factory(3, [
            'price' => 200
        ])->create();

        $response = $this->get('/api/products?max_price=100');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
        foreach ($response->json('data') as $product) {
            $this->assertLessThanOrEqual(100, $product['price']);
        }
    }

    public function test_index_with_min_price_filter(): void
    {
        Product::factory(2, [
            'price' => 100,
        ])->create();
        Product::factory(3, [
            'price' => 200,
        ])->create();

        $response = $this->get('/api/products?min_price=110');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
        foreach ($response->json('data') as $product) {
            $this->assertGreaterThanOrEqual(110, $product['price']);
        }
    }

    public function test_index_with_title_filter(): void
    {
        Product::factory(2, [
            'title' => 'Some title',
        ])->create();
        Product::factory(3, [
            'title' => 'Another title',
        ])->create();

        $response = $this->get('/api/products?title=other');

        $response->assertStatus(200);
        foreach ($response->json('data') as $product) {
            $this->assertStringContainsString('other', $product['title']);
        }
    }

    public function test_show(): void
    {
        $this->seed();

        $response = $this->get('/api/products/1');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'description',
                'image_url',
                'price',
            ]
        ]);
    }
}
