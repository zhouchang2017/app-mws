<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSuppliesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('supplies')->truncate();
        DB::table('supply_items')->truncate();
        DB::table('statuses')->truncate();
        DB::table('notifications')->truncate();

        factory(\App\Models\Supply::class, 20)->make()->each(function ($supply) {
            $supplier = $this->randomSupplier();

            // 供应商登录
            auth()->login($supplier->users->random());
            $supply->origin()->associate($supplier);
            $supply->save();

            $loop = mt_rand(1, $supplier->variants()->count());
            for ($i = 1; $i <= $loop; $i++) {
                $variant = $supplier->variants->random();
                // create items
                $supply->items()->create([
                    'product_id' => $variant->product->id,
                    'variant_id' => $variant->id,
                    'quantity' => mt_rand(1, 100),
                ]);
            }
            // 提交审核
            $service = new \App\Services\SupplyService($supply);

            // 供应商主管登录
            auth()->login($supplier->manager);
            // 提交审核
            $service->statusToPending();

        });

        // 管理员通过未读消息来审核
        \App\Models\User::all()->each(function ($user) {
            // 管理员登录
            auth()->login($user);
            $user->supplyPendingNotify()->get()->each(function (
                $notifies
            ) {
                if ($notifies->count()> 0) {
                    $notifies->each(function ($notify) {
                        $data = $notify->data;

                        $service = new \App\Services\SupplyService(\App\Models\Supply::find($data['supply_id']));
                        $service->statusToApproved();
                        $notify->markAsRead();
                    });
                }
            });
        });
    }

    protected function randomSupplier()
    {
        $supplier = \App\Models\Supplier::all()->random();
        if ($supplier->variants()->count() > 0) {
            return $supplier;
        }
        return $this->randomSupplier();
    }
}
