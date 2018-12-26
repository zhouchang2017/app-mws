<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('supplier_product')->truncate();
        DB::table('supplier_variants')->truncate();

        \App\Models\DP\Product::all()->each(function ($product) {
            // 关联产品
            $supplier = \App\Models\Supplier::all()->random();
            $supplier->products()->attach($product);

            // 关联变体
            $product->variants->each(function ($variant) use ($supplier) {
                $supplier->variants()->attach($variant, ['hidden' => false,'name'=>$variant->getName()]);
            });
        });
    }
}
