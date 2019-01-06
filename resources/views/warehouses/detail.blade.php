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

    <resource-index-header
            via-relation-name="addressable"
            morph-type="warehouse"
            uri-key="addresses"
            via-relation-id="{{$resource->id}}"
            singular-label="地址"
            can-create
            label="地址"
    ></resource-index-header>
    <div class="form-list mb-6">
        @if($resource->address)
            <empty-resources>
                <h3 class="text-base text-80 font-normal mb-6">
                    暂无地址
                </h3>
                <a href="{{route($domain.'.addresses.create').'?viaRelationName=addressable&morphType=warehouse&viaRelationId='.$resource->id}}"
                   class="btn cursor-pointer btn-sm btn-outline inline-flex items-center">
                    创建地址
                </a>
            </empty-resources>
        @else
            <form-item title="名称" value="{{$resource->name }}"></form-item>
        @endif
    </div>
@endsection