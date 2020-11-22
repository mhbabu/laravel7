<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productCategories = [
            ['name' => 'Product Category One', 'status' => 1],
            ['name' => 'Product Category Two', 'status' => 1],
            ['name' => 'Product Category Three', 'status' => 1],
            ['name' => 'Product Category Four', 'status' => 1],
            ['name' => 'Product Category Five', 'status' => 1]
        ];
        DB::table('product_categories')->insert($productCategories);
    }
}
