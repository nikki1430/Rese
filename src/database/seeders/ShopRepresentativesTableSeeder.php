<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShopRepresentativesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'user_id' => '2',
            'shop_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('shop_representatives')->insert($param);
    }
}