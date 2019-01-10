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

    <div class="form-list mb-6">
        <form-item title="名称" value="{{$resource->name }}"></form-item>
        <form-item title="类型" value="{{$resource->type->name}}" uri-key="warehouse-types"
                   resource-id="{{$resource->type->id}}"></form-item>
        <form-item title="管理员" value="{{$resource->admin->name }}" uri-key="users"
                   resource-id="{{$resource->admin->id}}"></form-item>
        <form-item title="最后更新时间" value="{{$resource->updated_at}}"></form-item>
    </div>

    <detail-address :address='@json($resource->address)' api="{{route($domain.'.warehouses.address.store',['supplier'=>$resource])}}" label="地址"
                    ></detail-address>
@endsection