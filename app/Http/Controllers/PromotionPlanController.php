<?php

namespace App\Http\Controllers;

use App\Http\Requests\ErpRequest;
use App\Models\DP\PromotionPlan;
use App\Notifications\InvitePromotionPlanNotification;
use App\Services\PromotionService;
use Illuminate\Http\Request;
use App\Resources\PromotionPlan as PromotionPlanResource;

class PromotionPlanController extends Controller
{
    public static $resource = PromotionPlanResource::class;

    public function show(PromotionPlan $promotionPlan)
    {
        //WithDpPriceOfChannel
        $resource = $promotionPlan->loadMissing(['supplier', 'promotion', 'promotionVariants.variant.dpPrice','inviteLogs.causer']);
        if (request()->ajax()) {
            return response()->json($resource);
        }
        $this->viewShare((new static::$resource(static::$resource::newModel()))->authorizedToIndex(app(ErpRequest::class)));

        // 供应商没有确认 活动计划之前 看不到详情页，看的是签署同意计划的页面
        if(isSupplier() && is_null($promotionPlan->confirm_at)){
            return view(static::$resource::uriKey() . '.un_confirm', compact('resource'));
        }

        return view(static::$resource::uriKey() . '.detail', compact('resource'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param ErpRequest $request
     * @return \Illuminate\Http\Response
     */
    public function create(ErpRequest $request)
    {
        $this->viewShare();
        return view(static::$resource::uriKey() . '.create');
    }


    public function store(ErpRequest $request)
    {
        return $this->created(
            PromotionService::updateOrCreatePromotionPlan($request)
        );
    }

    public function notify(PromotionPlan $promotionPlan, ErpRequest $request)
    {
        $promotionPlan->supplier->users->each->invitePromotionPlanNotify($promotionPlan,
            $request->get('title'), $request->get('body')
        );
        activity('invite')
            ->performedOn($promotionPlan)
            ->causedBy(auth()->user())
            ->withProperties(['title' => $request->get('title'),'body'=>$request->get('body')])
            ->log('推送促销计划邀请');
        return response()->noContent();
    }

    public function confirm(PromotionPlan $promotionPlan,ErpRequest $request)
    {
        $promotionPlan->markAsConfirm();
        return redirect()->route($request->getSubDomain() . '.'.static::$resource::uriKey().'.show', [$promotionPlan]);
    }
}
