@extends('layouts.app')

@section('content')

    <resource-detail-header
            label-name="{{$resource->type->name}}(检测入库)"
            resource-name="pre-inventory-action-orders"
            resource-id="{{$resource->id}}"
            :can-destroy="false"
            :can-update="false"
    ></resource-detail-header>
    <div class="form-list mb-6">
        <form-item title="描述信息" value="{{$resource->description}}"></form-item>
        <form-item title="仓库" value="{{$resource->warehouse->name}}"></form-item>
        <form-item title="仓库负责人" value="{{$resource->warehouse->admin->name}}"></form-item>
        <form-item title="是否发货" value="{{$resource->hasTracks() ? '已发货' : '待发货'}}"></form-item>
        <form-item title="最后更新时间" value="{{$resource->updated_at}}"></form-item>
    </div>

    <pre-inventory-action-order-check :resource='@json($resource)'></pre-inventory-action-order-check>
@endsection
