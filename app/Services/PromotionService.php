<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2019/1/8
 * Time: 5:23 PM
 */

namespace App\Services;


use App\Models\DP\Promotion;
use App\Models\DP\PromotionPlan;
use App\Models\DP\PromotionVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PromotionService
{
    public static function updateOrCreatePromotionPlan(Request $request, PromotionPlan $promotionPlan = null)
    {
        return DB::transaction(function () use ($request, $promotionPlan) {
            return tap($promotionPlan ?? new PromotionPlan(), function ($plan) use ($request) {
                /** @var PromotionPlan $plan */
                $plan->fill($request->all());
                $plan->save();
                if (count($request->get('variants')) > 0) {
                    collect($request->get('variants'))->each(function ($data) use ($plan) {
                        static::updateOrCreatePromotionVariant($data, $plan);
                    });
                }
            });
        });
    }

    public static function updateOrCreatePromotionVariant(
        array $data,
        PromotionPlan $promotionPlan,
        PromotionVariant $promotionVariant = null
    ) {
        tap($promotionVariant ?? new PromotionVariant(), function ($variant) use ($data, $promotionPlan) {
            /** @var PromotionVariant $variant */
            $type = $promotionPlan->promotion->type;
            $variant->fill(array_merge($data, [
                'promotion_id' => $promotionPlan->promotion->id,
                'promotion_type' => $promotionPlan->promotion->type,
            ], static::resolveVariantDiscountRate(array_get($data, 'surrender_rate'), $type)));
            $variant->plan()->associate($promotionPlan);
            $variant->save();
        });
    }

    public static function resolveVariantDiscountRate($surrenderRate, string $type)
    {
        if ($type === Promotion::KOL_PROMOTE) {
            info('resolve: KOL_PROMOTE ' . $surrenderRate);
            return ['discount_rate' => $surrenderRate * 0.5];
        }
        return ['discount_rate' => $surrenderRate];
    }
}