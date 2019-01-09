@extends('layouts.app')

@section('content')

    <resource-detail-header
            uri-key="{{$uriKey}}"
            label="{{$label}}"
            singular-label="{{$singularLabel}}"
            resource-id="{{$resource->id}}"
            :can-update='@json($resource->authorize['canUpdate'])'
            :can-destroy='@json($resource->authorize['canDestroy'])'
    ></resource-detail-header>
    <div class="form-list mb-6">
        <form-item title="计划说明" value="{{$resource->description}}"></form-item>
        <form-item title="当前状态" value="{{$resource->current_state}}"></form-item>
        <form-item title="运输方式" value="{{$resource->has_ship ? '物流/快递' : '无需物流' }}"></form-item>
        <form-item title="更新时间" value="{{$resource->updated_at }}"></form-item>
    </div>

    <card-title label="计划来源供应商"></card-title>
    <div class="form-list mb-6">
        <form-item title="名称" value="{{$resource->origin->name}}"></form-item>
        <form-item title="编码" value="{{$resource->origin->code}}"></form-item>
        <form-item title="运输方式" value="{{$resource->has_ship ? '物流/快递' : '无需物流' }}"></form-item>
        <form-item title="更新时间" value="{{$resource->updated_at }}"></form-item>
    </div>

    @admin
        <card-title label="状态记录"></card-title>
        @component('components.statuses',['statuses'=>$resource->statuses])
        @endcomponent
    @endadmin

    <card-title label="入库商品列表"></card-title>

    <div class="card w-full mb-6">
        <div class="p-6">
            <product-variant-list :items='@json($resource->items)'></product-variant-list>
        </div>
        {{--未提交，显示提交审核按钮--}}
        @if ($resource->state->name === \App\Models\Supply::UN_COMMIT && auth()->user() instanceof \App\Models\SupplierUser)
            <div class="bg-30 flex px-8 py-4">
                <form class="ml-auto" action="{{ route($domain.'.supplies.submit',['supply'=>$resource->id]) }}"
                      method="POST"
                >
                    @method('PATCH')
                    @csrf
                    <button type="submit" class="btn btn-default btn-primary inline-flex items-center relative">
                    <span class="">
                        提交入库计划
                    </span>
                    </button>
                </form>
            </div>
        @endif

        {{--已提交，显示审核按钮--}}
        @if ($resource->state->name === \App\Models\Supply::PENDING && auth()->user() instanceof \App\Models\User)
            <div class="bg-30 flex px-8 py-4">
                <form class="ml-auto" action="{{ route($domain.'.supplies.approved',['supply'=>$resource->id]) }}"
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

        {{--已发货，显示完成按钮--}}
        @if ($resource->status === \App\Models\Supply::SHIPPED && $resource->isAllChecked() && auth()->user() instanceof \App\Models\User)
            <div class="bg-30 flex px-8 py-4">
                <form class="ml-auto" action="{{ route($domain.'.supplies.completed',['supply'=>$resource->id]) }}"
                      method="POST"
                >
                    @method('PATCH')
                    @csrf
                    <button type="submit" class="btn btn-default btn-primary inline-flex items-center relative">
                    <span class="">
                        标记完成(确认收货)
                    </span>
                    </button>
                </form>
            </div>
        @endif
    </div>
    @if($resource->canShowOrders() && $resource->preInventoryAction->orders()->count() > 0)
        @foreach ($resource->preInventoryAction->orders as $order)
            <resource-detail-header
                    label-name="入库单{{$loop->index + 1}}"
                    resource-name="pre-inventory-action-orders"
                    :can-destroy="false"
                    :can-update="false"
                    resource-id="{{$order->id}}"
            ></resource-detail-header>
            <div class="form-list mb-6 p-0">
                <div class="p-6">
                    <form-item title="接收仓库" value="{{$order->warehouse->name}}"></form-item>
                    {{--<form-item title="仓库地址" value="{{$order->warehouse->simple_address}}"></form-item>--}}
                    <form-item title="物流状态" value="{{$order->hasTracks() ? '已发货' : '待发货'}}"></form-item>
                    <form-item title="操作单">
                        <product-variant-list slot="value" :items='@json($order->items)'></product-variant-list>
                    </form-item>
                </div>
                {{-- 待发货，显示发货操作按钮--}}
                <div class="bg-30 flex px-8 py-4 ">
                    <a href="{{route($domain.'.supplies.order.shipment.create',['supply'=>$resource->id,'order'=>$order->id])}}"
                       class="btn btn-a btn-default ml-auto cursor-pointer text-white bg-primary"
                       title="Ship">
                        {{$order->hasTracks() ? '物流详情' : '发货'}}
                    </a>
                </div>

            </div>


        @endforeach
    @endif
@endsection
