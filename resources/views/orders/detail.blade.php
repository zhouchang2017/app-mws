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
        <form-item title="ID" value="{{$resource->id}}"></form-item>
        <form-item title="订单渠道" value="{{$resource->market->name}}" uri-key="markets" resource-id="{{$resource->market->id}}"></form-item>
        @if($resource->origin instanceof \App\Models\DP\Order)
        <form-item title="源" value="{{$resource->type_name.$resource->origin->number ?? $resource->origin->id }}" uri-key="markets" resource-id="{{$resource->market->id}}"></form-item>
        @endif
        <form-item title="最后更新时间" value="{{$resource->updated_at}}"></form-item>
    </div>

    {{--<card-title label="{{$resource->typeName}}"></card-title>--}}
    {{--<div class="form-list mb-6">--}}
        {{--@if($resource->marketable instanceof \App\Models\DP\Channel)--}}
        {{--<form-item title="ID" value="{{$resource->marketable->id}}"></form-item>--}}
        {{--<form-item title="名称" value="{{$resource->marketable->name}}"></form-item>--}}
        {{--<form-item title="编码" value="{{$resource->marketable->code}}"></form-item>--}}
        {{--<form-item title="LOCALE CODE" value="{{$resource->marketable->locale_code}}"></form-item>--}}
        {{--<form-item title="货币" value="{{$resource->marketable->currency_code}}"></form-item>--}}
        {{--<form-item title="描述" value="{{$resource->marketable->description}}"></form-item>--}}
        {{--<form-item title="EMAIL" value="{{$resource->marketable->email}}"></form-item>--}}
        {{--<form-item title="最后更新时间" value="{{$resource->marketable->updated_at}}"></form-item>--}}
        {{--@endif--}}
    {{--</div>--}}
@endsection