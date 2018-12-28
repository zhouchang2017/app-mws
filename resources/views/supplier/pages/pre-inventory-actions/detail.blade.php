@extends('layouts.app')

@section('content')
    <div>

        <h3 class="text-80 py-3 ml-3">入库计划详情</h3>
        <div class="card p-6 w-full">
            <div class="flex border-b border-40 text-80">
                <div class="w-1/5 py-6 px-8">
                    计划说明
                </div>
                <div class="py-6 px-8" >
                    {{$resource->description}}
                </div>
            </div>
        </div>
        @foreach ($resource->orders as $order)
            <h3 class="text-80 py-3 ml-3">明细 ID{{$order->id}}</h3>
            <div class="card p-6 w-full">
                <div class="flex border-b border-40 text-80">
                    <div class="w-1/5 py-6 px-8">
                        接收仓库
                    </div>
                    <div class="py-6 px-8" >
                        {{$order->warehouse->name}}
                    </div>
                </div>
                <div class="flex border-b border-40 text-80">
                    <div class="w-1/5 py-6 px-8">
                        入库单明细
                    </div>
                    <div class="w-4/5 py-6 px-8" >
                        <el-table
                                :data='{{ json_encode($order->items) }}'
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
                </div>
                <div class="flex text-80">
                    <div class="w-1/5 py-6 px-8">
                        物流信息
                    </div>
                    <div class="w-4/5 py-6 px-8" >
                        <el-table
                                :data='@json($order->tracks)'
                                >
                            <el-table-column
                                    prop="logistic.name"
                                    label="物流公司"
                                    >
                            </el-table-column>
                            <el-table-column
                                    prop="tracking_number"
                                    label="物流单号"
                                    >
                            </el-table-column>
                        </el-table>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
@endsection
