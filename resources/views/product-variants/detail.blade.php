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
        <form-item title="变体名称" value="{{$resource->name ?? $resource->variantName}}"></form-item>
        <form-item title="变体编码" value="{{$resource->code}}"></form-item>
        <form-item title="价格" value="{{optional($resource->price)->price}}"></form-item>
        <form-item title="产品分类" value="{{$resource->product->name }}"></form-item>
        <form-item title="宽度" value="{{$resource->width .'Cm(厘米)'}}"></form-item>
        <form-item title="高度" value="{{$resource->height .'Cm(厘米)'}}"></form-item>
        <form-item title="长度" value="{{$resource->length .'Cm(厘米)'}}"></form-item>
        <form-item title="重量" value="{{$resource->weight .'Kg(公斤)'}}"></form-item>
        <form-item title="最后更新时间" value="{{$resource->updated_at}}"></form-item>
    </div>

    <card-title label="变体销售属性"></card-title>
    <div class="form-list mb-6">
        @foreach($resource->optionValues as $optionValue)
            <form-item title="{{$optionValue->option->name}}" value="{{$optionValue->value ?? 'N/A'}}"></form-item>
        @endforeach
    </div>

@endsection