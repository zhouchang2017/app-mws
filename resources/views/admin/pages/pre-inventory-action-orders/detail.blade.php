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
        <form-item title="最后更新时间" value="{{$resource->updated_at}}"></form-item>
    </div>


    <card-title label-name="入库商品列表"></card-title>
    <div class="card w-full mb-6">
        <div class="p-6">
            <product-variant-list :items='@json($resource->items)' ></product-variant-list>
        </div>

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
