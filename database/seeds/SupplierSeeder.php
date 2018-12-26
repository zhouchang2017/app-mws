<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 随机
        DB::table('suppliers')->truncate();
        DB::table('supplier_users')->truncate();

        factory(\App\Models\Supplier::class, 30)->create()->each(function ($supplier) {

            $supplier->users()->saveMany(factory(\App\Models\SupplierUser::class, mt_rand(1, 5))->make());
            // warehouse address
            $supplier->addresses()->save(factory(\App\Models\Address::class)->make([
                'collection_name' => 'warehouse',
            ]));
            // office address
            $supplier->addresses()->save(factory(\App\Models\Address::class)->make([
                'collection_name' => 'office',
            ]));
            // 随机取个用户作为该供应商管理员
            $supplier->manager()->associate($supplier->users->random());
            // 官方小二
            $supplier->admin()->associate(\App\Models\User::all()->random());
            $supplier->save();
        });


        $user = \App\Models\SupplierUser::all()->random();
        $user->email = '290621352@qq.com';
        $user->save();

    }
}
