<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $orders = [];

        for ($i = 0; $i < 10000000; $i++) {
            $orders[] = [
                'order_name' => $faker->word,
                'quantity' => $faker->numberBetween(1, 100),
                'price' => $faker->randomFloat(2, 10, 1000),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if ($i % 10000 == 0) {
                // Insert 10,000 records at once
                Order::insert($orders);
                $orders = [];
            }
        }

        // Insert any remaining records
        if (!empty($orders)) {
            Order::insert($orders);
        }
    }
}
