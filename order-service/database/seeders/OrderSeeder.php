<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('orders')->insert([
            [
                'id' => 1,
                'customer_id' => 1,
                'total' => 112.80,
            ],
            [
                'id' => 2,
                'customer_id' => 2,
                'total' => 219.75,
            ],
            [
                'id' => 3,
                'customer_id' => 3,
                'total' => 1275.18,
            ],
        ]);
    }
}
