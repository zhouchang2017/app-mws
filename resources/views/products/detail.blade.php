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
        <form-item title="产品名称" value="{{$resource->name}}"></form-item>
        <form-item title="产品编码" value="{{$resource->code}}"></form-item>
        <form-item title="产品分类" value="{{$resource->taxon->name }}"></form-item>
        <form-item title="最后更新时间" value="{{$resource->updated_at}}"></form-item>
    </div>

    <card-title label="产品属性"></card-title>
    <div class="form-list mb-6">
        @foreach($resource->attributeValues as $attribute)
            <form-item title="{{$attribute->attribute->name . '('.$attribute->locale_code.')'}}" value="{{$attribute->text_value}}"></form-item>
        @endforeach
    </div>

    <card-title label="产品销售属性"></card-title>
    <div class="form-list mb-6">
        <form-item title="OPTIONS" value="{{$resource->options->map->name->implode(',')}}"></form-item>
    </div>

    <resource-index-header
            via-relation-name="product"
            uri-key="product-variants"
            via-relation-id="{{$resource->id}}"
            can-create
            label="变体"
    ></resource-index-header>
    <div class="form-list mb-6">
        @if($resource->variants->count() === 0)
        <empty-resources>
            <h3 class="text-base text-80 font-normal mb-6">
                暂无变体
            </h3>
            <a class="btn cursor-pointer btn-sm btn-outline inline-flex items-center">
                创建变体
            </a>
        </empty-resources>
        @else
        <el-table
                :data='@json($resource->variants)'
        >
            <el-table-column
                    prop="variantName"
                    label="商品名称"
            >
            </el-table-column>
            <el-table-column
                    prop="code"
                    label="商品编码"
            >
            </el-table-column>
            <el-table-column
                    prop="stock"
                    label="库存"
            >
            </el-table-column>
        </el-table>
        @endif
    </div>
@endsection