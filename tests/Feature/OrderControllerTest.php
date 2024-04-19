<?php

namespace Tests\Feature;

use App\Events\OrderCreated;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_show(): void
    {
        $this->seed();

        $this->actingAs(Order::find(1)->user);
        $response = $this->getJson('/api/orders/1');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'user',
                'items',
                'total',
                'created_at',
                'updated_at',
            ],
        ]);
        $response->assertDontSee('password');
    }

    public function test_store(): void
    {
        $this->seed();
        $this->actingAs(User::find(1));

        $response = $this->postJson('/api/orders', [
            'items' => [
                [
                    'product_id' => 1,
                    'quantity' => 1
                ],
                [
                    'product_id' => 2,
                    'quantity' => 2
                ],
            ],
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'data' => [
                'status' => 'created',
                'user' => [
                    'id' => 1,
                ],
                'items' => [
                    [
                        'product' => ['id' => 1],
                        'quantity' => 1,
                    ],
                    [
                        'product' => ['id' => 2],
                        'quantity' => 2,
                    ],
                ],
            ],
        ]);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'user',
                'items' => [
                    '*' => [
                        'product' => [
                            'id',
                            'title',
                        ],
                        'price',
                    ],
                ],
                'total',
                'created_at',
                'updated_at',
            ],
        ]);
    }

    public function test_store_dispatches_event(): void
    {
        $this->seed();
        $this->actingAs(User::find(1));

        Event::fake();
        $response = $this->postJson('/api/orders', [
            'items' => [
                [
                    'product_id' => 1,
                    'quantity' => 1
                ]
            ],
        ]);

        $response->assertStatus(201);
        Event::assertDispatched(OrderCreated::class);
    }


    public function test_store_fails_if_not_enough_in_stock(): void
    {
        Product::factory()->create([
            'title' => 'Product 1',
            'price' => 100,
            'quantity' => 1,
        ]);
        Product::factory()->create([
            'title' => 'Product 2',
            'price' => 200,
            'quantity' => 2,
        ]);
        User::factory()->create();


        $this->actingAs(User::find(1));
        $response = $this->postJson('/api/orders', [
            'items' => [
                [
                    'product_id' => 1,
                    'quantity' => 5
                ],
                [
                    'product_id' => 2,
                    'quantity' => 2
                ],
            ],
        ]);

        $response->assertStatus(409);
        $response->assertJson([
            'message' => 'Not enough in stock',
            'data' => [
                [
                    'product_id' => 1,
                    'available_quantity' => 1,
                ]
            ],
        ]);;
    }

}
