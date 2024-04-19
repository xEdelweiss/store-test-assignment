<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_ordersHistory(): void
    {
        $this->seed();
        $this->actingAs(User::find(1));

        $response = $this->get('/api/cabinet/orders');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'status',
                    'items_count',
                    'total',
                    'updated_at',
                ],
            ],
        ]);
        foreach ($response->json('data') as $order) {
            $this->assertEquals(1, Order::find($order['id'])->user_id);
        }
    }

    public function test_update()
    {
        User::factory(['name' => 'Old name'])->create();

        $this->actingAs(User::find(1));
        $response = $this->put('/api/cabinet/profile', [
            'name' => 'New name',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => 1,
                'name' => 'New name',
            ],
        ]);
        $this->assertEquals('New name', User::find(1)->name);
    }
}
