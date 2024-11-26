<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RandomUserIdAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $userIds = User::pluck('id')->toArray();

        // Get all orders and assign random user_id
        Order::chunk(1000, function ($orders) use ($userIds) {
            foreach ($orders as $order) {
                $order->user_id = $userIds[array_rand($userIds)]; // Assign random user_id
                $order->save();
            }
        });
    }
}
