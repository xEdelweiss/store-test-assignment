<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::factory(10)
            ->for(User::factory())
            ->has(OrderItem::factory()->count(random_int(2, 5)), 'items')
            ->create();
    }
}
