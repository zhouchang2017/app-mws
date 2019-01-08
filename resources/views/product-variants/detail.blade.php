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
            <translation-detail-item title="{{$optionValue->option->name}}" attribute="value"
                                     :translations='@json($optionValue->translations)'></translation-detail-item>
        @endforeach
    </div>

    <card-title label="变体渠道价格"></card-title>
    <div class="form-list mb-6">
        @admin
        <edit-dp-channel-price :default-value='@json($resource->dpPrices)' resource-id="{{$resource->id}}">

        </edit-dp-channel-price>
        @endadmin

        @supplier
            @if($resource->dpPrices()->count()>0)

                <el-table
                        :data='@json($resource->dpPrices)'
                        style="width: 100%">
                    <el-table-column
                            label="吊牌价"
                            prop="original_price">
                    </el-table-column>
                    <el-table-column
                            label="售价"
                            prop="price"
                    >
                    </el-table-column>
                    <el-table-column
                            label="DP渠道"
                            prop="channel.name"
                    >
                    </el-table-column>
                </el-table>
            @else
                <empty-resources message="暂无渠道价格"/>
            @endif
        @endsupplier

    </div>

@endsection