<?php

namespace App\Http\Controllers;

use App\Http\Requests\ErpRequest;
use App\Models\Withdraw;
use App\Services\WithdrawService;
use Illuminate\Http\Request;
use App\Resources\Withdraw as WithdrawResource;

class WithdrawController extends Controller
{

    public static $resource = WithdrawResource::class;


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->viewShare();
        return view(static::$resource::uriKey() . '.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        return $this->created(
            WithdrawService::updateOrCreateWithdraw($request)
        );
    }

    /**
     * Display the specified resource.
     *
     * @param Withdraw $withdraw
     * @return \Illuminate\Http\Response
     */
    public function show(Withdraw $withdraw)
    {
        $resource = $withdraw->loadMissing(['items', 'warehouse', 'items.variant', 'statuses.user', 'origin']);
        if (request()->ajax()) {
            return response()->json($resource);
        } else {
            $this->viewShare();
            return view(static::$resource::uriKey() . '.detail', compact('resource'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @param Withdraw $withdraw
     * @param ErpRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Spatie\ModelStatus\Exceptions\InvalidStatus
     */
    public function submit(Withdraw $withdraw, ErpRequest $request)
    {
        (new WithdrawService($withdraw))->statusToPending();
        if (request()->ajax()) {
            return $this->updated([], '退仓申请已提交', '您的提交会尽快处理,请耐心等待');
        } else {
            return redirect()->route($request->getSubDomain() . '.' . static::$resource::uriKey() . '.show',
                ['withdraw' => $withdraw]);
        }
    }

    /**
     * @param Withdraw $withdraw
     * @param ErpRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Spatie\ModelStatus\Exceptions\InvalidStatus
     */
    public function approved(Withdraw $withdraw, ErpRequest $request)
    {
        (new WithdrawService($withdraw))->statusToApproved();
        if (request()->ajax()) {
            return $this->updated([], '审核完成');
        } else {
            return redirect()->route($request->getSubDomain() . '.' . static::$resource::uriKey() . '.show',
                ['withdraw' => $withdraw]);
        }
    }


    /**
     * @param Withdraw $withdraw
     * @param ErpRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Spatie\ModelStatus\Exceptions\InvalidStatus
     */
    public function completed(Withdraw $withdraw, ErpRequest $request)
    {
        (new WithdrawService($withdraw))->statusToCompleted();
        if ($request->ajax()) {
            return $this->updated(
                [],
                '退仓完成'
            );
        }
        return redirect()->route($request->getSubDomain() . '.' . static::$resource::uriKey() . '.show',
            ['withdraw' => $withdraw]);

    }


}
