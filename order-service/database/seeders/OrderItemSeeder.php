<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('order_items')->insert([
            [
                'order_id' => 1,
                'product_id' => 102,
                'quantity' => 10,
                'unit_price' => 11.28,
                'total' => 112.80,
            ],
            [
                'order_id' => 2,
                'product_id' => 101,
                'quantity' => 2,
                'unit_price' => 49.50,
                'total' => 99.00,
            ],
            [
                'order_id' => 2,
                'product_id' => 100,
                'quantity' => 1,
                'unit_price' => 120.75,
                'total' => 120.75,
            ],
            [
                'order_id' => 3,
                'product_id' => 102,
                'quantity' => 6,
                'unit_price' => 11.28,
                'total' => 67.68,
            ],
            [
                'order_id' => 3,
                'product_id' => 100,
                'quantity' => 10,
                'unit_price' => 120.75,
                'total' => 1207.50,
            ],
        ]);
    }
}
