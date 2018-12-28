@extends('layouts.app')

@section('content')

    <resource-detail-header
            label-name="入库计划详情"
            resource-name="supplies"
            resource-id="{{$resource->id}}"
    ></resource-detail-header>
    <div class="card p-6 w-full mb-6">
        <div class="flex border-b border-40 text-80">
            <div class="w-1/5 py-6 px-8">
                计划说明
            </div>
            <div class="py-6 px-8">
                {{$resource->description}}
            </div>
        </div>
        <div class="flex border-b border-40 text-80">
            <div class="w-1/5 py-6 px-8">
                当前状态
            </div>
            <div class="py-6 px-8">
                {{$resource->current_state}}
            </div>
        </div>
        <div class="flex border-b border-40 text-80">
            <div class="w-1/5 py-6 px-8">
                运输方式
            </div>
            <div class="py-6 px-8">
                {{$resource->has_ship ? '物流/快递' : '无需物流' }}
            </div>
        </div>
        <div class="flex  text-80">
            <div class="w-1/5 py-6 px-8">
                更新时间
            </div>
            <div class="py-6 px-8">
                {{$resource->updated_at}}
            </div>
        </div>
    </div>
    <card-title label-name="入库商品列表"></card-title>
    <div class="card w-full">
        <div class="p-6">
            <el-table
                    :data='@json($resource->items)'
            >
                <el-table-column
                        prop="variant.variantName"
                        label="商品名称"
                >
                </el-table-column>
                <el-table-column
                        prop="quantity"
                        label="数量"
                >
                </el-table-column>

            </el-table>

        </div>
        <div class="bg-30 flex px-8 py-4">
            <button type="button" class="btn btn-default btn-primary inline-flex items-center relative ml-auto">
                <span class="">
                    提交入库计划
                </span>
            </button>
        </div>
    </div>
@endsection
