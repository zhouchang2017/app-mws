<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WarehousesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('warehouse_types')->truncate();
        DB::table('warehouses')->truncate();
        $this->createWarehouseTypes();
        factory(\App\Models\Warehouse::class,10)->make()->each(function($warehouse){
            $warehouse->type()->associate(\App\Models\WarehouseType::all()->random());
            $warehouse->admin()->associate(\App\Models\User::all()->random());
            $warehouse->save();

            $warehouse->address()->save(factory(\App\Models\Address::class)->make());

        });
    }

    protected function createWarehouseTypes()
    {
        DB::table('warehouse_types')->insert([
            [
                "name"=>'本地仓库',
                "description"=>"如果您使用的仓库是自己在运营，并且仓库的工作人员是直接登录本系统进行包裹的打印、拣货、包装、发货等，我们称此类仓库为“本地仓库”。如果该仓库并非自己运营，包裹是由第三方员工直接登录通途系统帮您处理，此种情况的仓库我们也称之为\"本地仓库\"。"
            ],
            [
                "name"=>'海外仓库',
                "description"=>"如果您有自营仓库在海外，并且同样需要在本系统中进行管理。可以在这里建立一个海外仓库,除了能使用采购建议向海外仓进行补货功能以外,它还提供了中转仓与海外仓间的货物采购,调拨,装卸箱,收发货等管理功能。对于此类仓库,我们称之为\"海外仓库\"。"
            ],
            [
                "name"=>'第三方仓库',
                "description"=>"1、如果您使用的仓库是由第三方公司运营，如出口易，递四方等。只需要在列表中选择对应的第三方仓库并启用。系统会通过第三方公司提供的API接口或支持导入的Excel文件完成订单的传递及通知。对方会根据传递的订单完成打印、拣货、包装、发货等工作。对于此类仓库我们称之为“第三方仓库”。2、温馨提示：已支持库存同步的第三方仓库包括：递四方所有海外仓、万欧国际英国仓和中邮美西仓库。"
            ],
            [
                "name"=>'FBA仓库',
                "description"=>"需要关联亚马逊开发者账号"
            ],
        ]);
    }
}
