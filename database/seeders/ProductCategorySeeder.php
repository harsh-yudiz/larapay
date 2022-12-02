<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product_categories')->truncate();

        $data = [
            ['name' => 'PHYSICAL'],
            ['name' => 'DIGITAL'],
            ['name' => 'SERVICE'],
        ];
        DB::table('product_types')->insert($data);
    }
}
