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
        <form-item title="昵称" value="{{$resource->name}}"></form-item>
        <form-item title="email" value="{{$resource->email}}"></form-item>
        <form-item title="手机" value="{{$resource->phone }}"></form-item>
        <form-item title="供应商" value="{{$resource->supplier->name }}" uri-key="suppliers" resource-id="{{$resource->supplier->id}}"></form-item>
        <form-item title="更新时间" value="{{$resource->updated_at}}"></form-item>
    </div>

@endsection