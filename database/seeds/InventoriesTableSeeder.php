<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('inventory_actions')->truncate();
        DB::table('inventories')->truncate();
        factory(\App\Models\InventoryAction::class, 500)->make()->each(function ($action) {
            $type = \App\Models\InventoryActionType::all()->random();
            $action->type()->associate($type);
            $action->action_type_name = $type->action;
            $action->warehouse()->associate(\App\Models\Warehouse::all()->random());
            $action->warehouse_area = mt_rand(0, 95) === 0 ? 'bad' : 'good';
            $action->save();
        });
    }
}
