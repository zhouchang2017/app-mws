@extends('layouts.app')

@section('content')
    <resource-detail-header
            label-name="入库计划详情"
            resource-name="supplies"
            resource-id="{{$resource->id}}"
            :can-destroy="{{+($resource->state->name === \App\Models\Supply::UN_COMMIT)}}"
            :can-update="{{+($resource->state->name === \App\Models\Supply::UN_COMMIT)}}"
    ></resource-detail-header>

    <div class="form-list mb-6">
        <form-item title="计划说明" value="{{$resource->description}}"></form-item>
        <form-item title="当前状态" value="{{$resource->current_state}}"></form-item>
        <form-item title="运输方式" value="{{$resource->has_ship ? '物流/快递' : '无需物流' }}"></form-item>
        <form-item title="最后更新时间" value="{{$resource->updated_at}}"></form-item>
    </div>

    <card-title label-name="入库商品列表"></card-title>
    <div class="card w-full mb-6">
        <div class="p-6">
            <product-variant-list :items='@json($resource->items)'></product-variant-list>
        </div>
        {{--未提交，显示提交审核按钮--}}
        @if ($resource->state->name === \App\Models\Supply::UN_COMMIT)
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
    </div>

    @if($resource->canShowOrders())
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
                    <a href="{{route('supplier.supplies.order.shipment.create',['supply'=>$resource->id,'order'=>$order->id])}}"
                       class="btn btn-a btn-default ml-auto cursor-pointer text-white bg-primary"
                       title="Ship">
                        {{$order->hasTracks() ? '物流详情' : '发货'}}
                    </a>
                </div>

            </div>


        @endforeach
    @endif
@endsection
