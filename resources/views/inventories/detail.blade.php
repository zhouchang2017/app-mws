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
        <form-item title="产品名称" value="{{$resource->product->name}}"
                   uri-key="products"
                   resource-id="{{$resource->product->id}}"
        ></form-item>
        <form-item title="变体名称" value="{{$resource->variant->name}}"
                   uri-key="product-variants"
                   resource-id="{{$resource->variant->id}}"
        ></form-item>
        <form-item title="仓库" value="{{$resource->warehouse->name}}"
                   uri-key="warehouses"
                   resource-id="{{$resource->warehouse->id}}"
        ></form-item>
        <form-item title="余量" value="{{$resource->quantity}}"
        ></form-item>
        <form-item title="良品/不良品">
            <boolean-field slot="value"
                           :value='@json($resource->warehouse_area)'
                           true-value='good'
            ></boolean-field>
        </form-item>
        <form-item title="更新时间" value="{{$resource->updated_at}}"></form-item>
    </div>

    <card-title label="历史"></card-title>
    <div class="form-list mb-6">
        <inventory-history warehouse-id="{{$resource->warehouse_id}}" variant-id="{{$resource->variant_id}}"></inventory-history>
    </div>
@endsection