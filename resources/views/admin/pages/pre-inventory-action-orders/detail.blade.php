@extends('layouts.app')

@section('content')

    <resource-detail-header
            label-name="操作单({{$resource->type->name}})"
            resource-name="pre-inventory-action-orders"
            resource-id="{{$resource->id}}"
    ></resource-detail-header>

    <div class="form-list mb-6">
        <form-item title="描述信息" value="{{$resource->description}}"></form-item>
        <form-item title="仓库" value="{{$resource->warehouse->name}}"></form-item>
        <form-item title="仓库负责人" value="{{$resource->warehouse->admin->name}}"></form-item>
        <form-item title="是否发货" value="{{$resource->hasTracks() ? '已发货' : '待发货'}}"></form-item>
        <form-item title="最后更新时间" value="{{$resource->updated_at}}"></form-item>
    </div>


    <card-title label-name="入库商品列表"></card-title>
    <div class="card w-full mb-6">
        <div class="p-6">
            <product-variant-list :items='@json($resource->items)' ></product-variant-list>
        </div>
        {{-- 已发货，显示到货检测按钮--}}
        @if ($resource->hasTracks() && $resource->type->isPut())
            <div class="bg-30 flex px-8 py-4 ">
                <a  href="{{ route($domain.'.pre-inventory-action-orders.check',['pre-inventory-action-order'=>$resource->id]) }}"
                    class="btn btn-a btn-default ml-auto cursor-pointer text-white bg-primary"
                    title="Assign">
                    货品检测
                </a>
            </div>
        @endif
    </div>

    <card-title label-name="物流信息"></card-title>
    <div class="form-list">
        @if($resource->tracks()->count() > 0)
            <el-table
                    :data='@json($resource->tracks)'
            >
                <el-table-column
                        prop="logistic.name"
                        label="物流公司"
                >
                </el-table-column>
                <el-table-column
                        prop="tracking_number"
                        label="物流单号"
                >
                </el-table-column>
            </el-table>
        @else
            <empty-resources>
                <h3 class="text-base text-80 font-normal mb-6">
                    暂无物流信息
                </h3>
            </empty-resources>
        @endif
    </div>

@endsection
