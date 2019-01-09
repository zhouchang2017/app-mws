@extends('layouts.app')

@section('content')
    <resource-detail-header
            uri-key="{{$uriKey}}"
            label="{{$label}}"
            singular-label="{{$singularLabel}}"
            resource-id="{{$resource->id}}"

            :can-update="@json($canUpdate)"
            :can-destroy="@json($canDestroy)"

    ></resource-detail-header>

    <div class="form-list mb-6">
        <form-item title="促销活动" value="{{$resource->promotion->name}}" @admin uri-key="promotions"
                   resource-id="{{$resource->promotion->id}}" @endadmin></form-item>
        <form-item title="供应商" value="{{$resource->supplier->name}}" @admin uri-key="suppliers"
                   resource-id="{{$resource->supplier->id}}" @endadmin></form-item>
        <form-item title="累计销量" value="{{$resource->sold}}"></form-item>
        <form-item title="累计金额" value="{{$resource->total_amount}}"></form-item>
        @admin
        <form-item title="供应商确认时间" value="{{$resource->confirm_at}}" left-center>
            <div slot="value">
                @if($resource->confirm_at)
                    <p class="text-90" >
                        {{$resource->confirm_at}}
                    </p>
                @else
                    <el-alert
                            :closable="false"
                            type="info"
                            show-icon>
                        <div slot="title" class="flex items-center w-full">
                            <div>推送消息给供应商</div>
                            <div class="ml-auto">
                                <invite-supplier-join-promotion-plan
                                        uri-key="{{$uriKey}}"
                                        resource-id="{{$resource->id}}"
                                ></invite-supplier-join-promotion-plan>
                            </div>
                        </div>
                    </el-alert>
                @endif
                    <el-table
                            :data='@json($resource->inviteLogs)'>
                        <el-table-column
                                prop="properties.title"
                                label="标题"
                        >
                        </el-table-column>
                        <el-table-column
                                prop="properties.body"
                                label="正文"
                        >
                        </el-table-column>
                        <el-table-column
                                prop="causer.name"
                                label="操作者"
                        >
                        </el-table-column>
                        <el-table-column
                                prop="created_at"
                                label="发送时间"
                        >
                        </el-table-column>
                    </el-table>
            </div>
        </form-item>
        @endadmin
    </div>

    <card-title label="计划商品明细"></card-title>
    <div class="form-list mb-6">
        <el-table
                :data='@json($resource->promotionVariants)'
        >
            <el-table-column
                    prop="id"
                    label="ID"
            >
            </el-table-column>
            <el-table-column
                    prop="variant.code"
                    label="商品编码"
            >

            </el-table-column>
            <el-table-column
                    prop="variant.name"
                    label="商品名称"
            >
            </el-table-column>
            <el-table-column
                    label="售价"
            >
                <template slot-scope="{row}">
                    @{{_.floor(row.variant.dp_price.price * row.surrender_rate / 100,2).toFixed(2)}}
                </template>
            </el-table-column>
            <el-table-column
                    prop="surrender_rate"
                    label="折扣率(%)"
            ></el-table-column>
            <el-table-column
                    prop="discount_rate"
                    label="KOL分成(%)"
            ></el-table-column>
            <el-table-column
                    prop="stock"
                    label="活动库存"
            ></el-table-column>
            <el-table-column
                    prop="sold"
                    label="销量"
            ></el-table-column>
            <el-table-column
                    prop="began_at"
                    label="开始时间"
            ></el-table-column>
            <el-table-column
                    prop="ended_at"
                    label="结束时间"
            ></el-table-column>
        </el-table>
    </div>

@endsection