@extends('layouts.app')

@section('content')

    <resource-detail-header
            label="{{$resource->type->name}}"
            uri-key="{{$uriKey}}"
            singular-label="{{$singularLabel}}"
            resource-id="{{$resource->id}}"
    ></resource-detail-header>

    <div class="form-list mb-6">
        <form-item title="计划说明" value="{{$resource->description}}"></form-item>
        <form-item title="当前状态" value="{{$resource->current_state}}"></form-item>
        @if($resource->origin instanceof \App\Models\Order)
            <form-item title="客户地址" value="{{$resource->origin->simple_address}}"></form-item>
        @endif
    </div>

    <card-title label="状态记录"></card-title>
    @component('components.statuses',['statuses'=>$resource->statuses])
    @endcomponent

    <card-title label="{{$resource->type->name.'列表'}}"></card-title>
    <div class="card w-full mb-6">
        <div class="p-6">
            <product-variant-list :items='@json($resource->origin->items)'></product-variant-list>
        </div>
        {{--已提交，显示审核按钮--}}
        @if ($resource->state->name === \App\Models\PreInventoryAction::PENDING)
            <div class="bg-30 flex px-8 py-4">
                <form class="ml-auto"
                      action="{{ route($domain.'.pre-inventory-actions.approved',['pre-inventory-action'=>$resource->id]) }}"
                      method="POST"
                >
                    @method('PATCH')
                    @csrf
                    <button type="submit" class="btn btn-default btn-primary inline-flex items-center relative">
                    <span class="">
                        审核通过
                    </span>
                    </button>
                </form>
            </div>
        @endif
        {{-- 以审核，显示分配仓库操作按钮--}}
        @if ($resource->state->name === \App\Models\PreInventoryAction::APPROVED)
            <div class="bg-30 flex px-8 py-4 ">
                <a  href="{{ route($domain.'.pre-inventory-actions.assign.create',['pre-inventory-action'=>$resource->id]) }}"
                    class="btn btn-a btn-default ml-auto cursor-pointer text-white bg-primary"
                    title="Assign">
                    分配仓库
                </a>
            </div>
        @endif
    </div>

    @foreach ($resource->orders as $order)
        <resource-detail-header
                label-name="操作单[id:{{$order->id}}]"
                uri-key="pre-inventory-action-orders"
                :can-destroy="false"
                :can-update="false"
                :can-view="true"
                resource-id="{{$order->id}}"
        ></resource-detail-header>
        <div class="form-list mb-6">
            <form-item title="{{$resource->type->isTake() ? '出货仓库':'接收仓库'}}" value="{{$order->warehouse->name}}"></form-item>
            <form-item title="物流状态" value="{{$order->hasTracks() ? '已发货' : '待发货'}}"></form-item>
            <form-item title="操作单">
                <product-variant-list slot="value" :items='@json($order->items)'></product-variant-list>
            </form-item>
        </div>
    @endforeach
@endsection
