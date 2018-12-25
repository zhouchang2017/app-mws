<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventoryActionTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('inventory_action_types')->truncate();

        DB::table('inventory_action_types')->insert([
            [
                'name'          => '供应商供货入库',
                'action'        => 'put',
                'is_accounting' => true,
            ],
            [
                'name'          => '订单销售出货',
                'action'        => 'take',
                'is_accounting' => true,
            ],
            [
                'name'          => '仓库调拨(入)',
                'action'        => 'put',
                'is_accounting' => false,
            ],
            [
                'name'          => '仓库调拨(出)',
                'action'        => 'take',
                'is_accounting' => false,
            ],
        ]);
    }
}
