@extends('layouts.app')

@section('content')
    <resource-detail-header
            uri-key="{{$uriKey}}"
            label="{{$label}}"
            singular-label="{{$singularLabel}}"
            resource-id="{{$resource->id}}"
            :can-destroy="false"
            :can-update="true"
    ></resource-detail-header>

    <div class="form-list mb-6">
        <form-item title="名字" value="{{$resource->name }}"></form-item>
        <form-item title="邮箱" value="{{$resource->email}}"></form-item>
        <form-item title="手机" value="{{optional($resource)->phone }}"></form-item>
        @if($resource instanceof \App\Models\SupplierUser)
            <form-item url="{{route('supplier.suppliers.profile')}}" title="供应商" value="{{$resource->supplier->name}}"></form-item>
        @endif
        <form-item title="微信" left-center>
            @if($resource->hasBind())
                <p class="text-90" slot="value">
                    {{$resource->wechat->nickname}}
                </p>
            @else
                <bind-wechat slot="value"></bind-wechat>
            @endif
        </form-item>
    </div>
@endsection