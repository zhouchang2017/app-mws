<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('addresses')->truncate();

        $this->call(UsersTableSeeder::class); // 官方用户
        $this->call(WarehousesSeeder::class); // 仓库
        $this->call(LogisticSeeder::class); // 物流公司
        $this->call(SupplierSeeder::class); // 供应商
        $this->call(SupplierProductsSeeder::class); // 供应商关联产品
//        $this->call(InventoryActionTypesTableSeeder::class);
//        $this->call(InventoriesTableSeeder::class);
    }
}
