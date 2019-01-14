@extends('layouts.app')

@section('content')

    <resource-detail-header
            uri-key="{{$uriKey}}"
            label="{{$label}}"
            singular-label="{{$singularLabel}}"
            resource-id="{{$resource->id}}"

            :can-destroy="true"
            :can-update="true"

    ></resource-detail-header>

    @if($resource->preInventoryAction)
        <div class="mb-3">
            <el-alert
                    title="{{$resource->preInventoryAction->current_state}}"
                    type="{{$resource->preInventoryAction->state_alert}}"
                    description="{{$resource->preInventoryAction->state->reason}}"
                    show-icon>
            </el-alert>
        </div>
    @endif

    <div class="form-list mb-6">
        <form-item title="ID" value="{{$resource->id}}"></form-item>
        <form-item title="订单渠道" value="{{$resource->market->name}}" uri-key="markets"
                   resource-id="{{$resource->market->id}}"></form-item>
        @if($resource->origin instanceof \App\Models\DP\Order)
            <form-item title="源" value="{{$resource->type_name.$resource->origin->number ?? $resource->origin->id }}"
                       uri-key="markets" resource-id="{{$resource->market->id}}"></form-item>
        @endif
        <form-item title="总价格" value="{{$resource->price}}"></form-item>
        <form-item title="订单状态" value="{{$resource->current_state}}"></form-item>
        <form-item title="收货地址" value="{{$resource->simple_address}}"></form-item>
        <form-item title="最后更新时间" value="{{$resource->updated_at}}"></form-item>
    </div>

    @admin
    <card-title label="状态记录"></card-title>
    @component('components.statuses',['statuses'=>$resource->statuses])
    @endcomponent
    @endadmin

    <card-title label="订单明细"></card-title>
    <div class="card w-full mb-6">
        <div class="p-6">
            <el-table show-summary :data='@json($resource->items)'>
                <el-table-column
                        prop="variant.code"
                        label="商品编码"
                >
                </el-table-column>
                <el-table-column
                        prop="variant.name"
                        label="商品名称"
                >
                </el-table-column>
                <el-table-column
                        prop="variant.current_price"
                        label="商品售价"
                >
                </el-table-column>
                <el-table-column
                        prop="price"
                        label="成交价"
                >
                </el-table-column>
                <el-table-column
                        prop="quantity"
                        label="数量"
                >
                </el-table-column>
                <el-table-column
                        align="right"
                        prop="created_at"
                        label="交易时间"
                >
                </el-table-column>
            </el-table>
        </div>
        {{--未提交，显示提交审核按钮--}}
        @if ($resource->status ===\App\Models\Order::UN_SHIP && auth()->user() instanceof \App\Models\User)
            <div class="bg-30 flex px-8 py-4">
                <form class="ml-auto"
                      action="{{route('admin.orders.createPreInventoryAction',['order'=>$resource])}}"
                      method="POST"
                >
                    @method('PATCH')
                    @csrf
                    <button type="submit" class="btn btn-default btn-primary inline-flex items-center relative">
                    <span class="">
                        生成出库单
                    </span>
                    </button>
                </form>
            </div>
        @endif
    </div>
@endsection