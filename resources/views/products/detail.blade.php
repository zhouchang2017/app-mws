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
            <form-item title="{{$attribute->attribute->name . '('.$attribute->locale_code.')'}}"
                       value="{{$attribute->text_value}}"></form-item>
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
            singular-label="变体"
            can-create
            label="变体"
    ></resource-index-header>
    <div class="form-list mb-6">
        @if($resource->variants->count() === 0)
            <empty-resources>
                <h3 class="text-base text-80 font-normal mb-6">
                    暂无变体
                </h3>
                <a href="{{route($domain.'.product-variants.create').'?viaRelationName=product&viaRelationId='.$resource->id}}"
                   class="btn cursor-pointer btn-sm btn-outline inline-flex items-center">
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
                        prop="price.price"
                        label="商品售价"
                >
                </el-table-column>
                <el-table-column
                        prop="stock"
                        label="库存"
                >
                </el-table-column>
                <el-table-column
                        align="right"
                        fixed="right"
                        label="操作"
                >
                    <template slot-scope="{row}">
                        <a class="cursor-pointer text-70 hover:text-primary" :class="{'mr-3':canUpdate}"
                           :href="`/product-variants/${row.id}`">
                            <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24"
                                 height="24">
                                <path class="heroicon-ui"
                                      d="M17.56 17.66a8 8 0 0 1-11.32 0L1.3 12.7a1 1 0 0 1 0-1.42l4.95-4.95a8 8 0 0 1 11.32 0l4.95 4.95a1 1 0 0 1 0 1.42l-4.95 4.95zm-9.9-1.42a6 6 0 0 0 8.48 0L20.38 12l-4.24-4.24a6 6 0 0 0-8.48 0L3.4 12l4.25 4.24zM11.9 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm0-2a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
                            </svg>
                        </a>
                        <a class="cursor-pointer text-70 hover:text-primary"
                           :href="`/product-variants/${row.id}/edit`">
                            <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24"
                                 height="24">
                                <path class="heroicon-ui"
                                      d="M6.3 12.3l10-10a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1 0 1.4l-10 10a1 1 0 0 1-.7.3H7a1 1 0 0 1-1-1v-4a1 1 0 0 1 .3-.7zM8 16h2.59l9-9L17 4.41l-9 9V16zm10-2a1 1 0 0 1 2 0v6a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6c0-1.1.9-2 2-2h6a1 1 0 0 1 0 2H4v14h14v-6z"/>
                            </svg>
                        </a>
                    </template>
                </el-table-column>
            </el-table>
        @endif
    </div>
@endsection