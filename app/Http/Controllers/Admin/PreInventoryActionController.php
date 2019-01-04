<?php

namespace App\Http\Controllers\Admin;

use App\Models\PreInventoryAction;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PreInventoryActionController extends Controller
{

    public static $resource = \App\Resources\PreInventoryAction::class;

    public static $indexViewName = 'admin.pages.pre-inventory-actions.index';


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param PreInventoryAction $preInventoryAction
     * @return \Illuminate\Http\Response
     */
    public function show(PreInventoryAction $preInventoryAction)
    {
        $resource = $preInventoryAction->loadOrders()->loadStatuses()->loadOriginItems()->loadType();
        if (request()->ajax()) {
            return response()->json($resource);
        }
        $this->viewShare();
        return view('admin.pages.pre-inventory-actions.detail', compact('resource'));
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
     * 审核
     * @param PreInventoryAction $preInventoryAction
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function approved(PreInventoryAction $preInventoryAction)
    {
        $preInventoryAction->statusToApproved();
        if (request()->ajax()) {
            return $this->updated([], '审核完成');
        } else {
            $this->viewShare();
            return redirect()->route('admin.pre-inventory-actions.show',
                [ 'pre-inventory-action' => $preInventoryAction->id ]);
        }
    }

    public function assign(PreInventoryAction $preInventoryAction)
    {
        $resource = $preInventoryAction->loadOrders()->loadStatuses()->loadOriginItems();
        $this->viewShare();
        return view('admin.pages.pre-inventory-actions.assign', compact('resource'));
    }

    public function assigned(PreInventoryAction $preInventoryAction, Request $request)
    {
        InventoryService::createPreActionOrder($preInventoryAction, $request);
        return $this->created([], '操作单已推入仓库中心');
    }
}
