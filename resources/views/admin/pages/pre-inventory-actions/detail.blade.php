@extends('layouts.app')

@section('content')

    <resource-detail-header
            label-name="{{$resource->type->name}}"
            resource-name="pre-inventory-actions"
            resource-id="{{$resource->id}}"
    ></resource-detail-header>

    <div class="form-list mb-6">
        @component('components.form-item',['title'=>'计划说明','body'=>$resource->description])
        @endcomponent
        @component('components.form-item',['title'=>'当前状态','body'=>$resource->current_state])
        @endcomponent
    </div>

    <card-title label-name="状态记录"></card-title>
    @component('components.statuses',['statuses'=>$resource->statuses])
    @endcomponent

    <card-title label-name="入库商品列表"></card-title>
    <div class="card w-full">
        <div class="p-6">
            <el-table
                    :data='@json($resource->origin->items)'
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
        {{--已提交，显示审核按钮--}}
        @if ($resource->state->name === \App\Models\PreInventoryAction::PENDING)
            <div class="bg-30 flex px-8 py-4">
                <form class="ml-auto"
                      action="{{ route($domain.'.pre-inventory-actions.approved',['pre-inventory-action'=>$resource->id]) }}"
                      method="POST"
                >
                    @method('PATCH')
                    @csrf
                    <button type="submit" class="btn btn-default btn-primary inline-flex items-center relative">
                    <span class="">
                        审核通过
                    </span>
                    </button>
                </form>
            </div>
        @endif
        {{-- 以审核，显示分配仓库操作按钮--}}
        @if ($resource->state->name === \App\Models\PreInventoryAction::APPROVED)
            <div class="bg-30 flex px-8 py-4 ">
                <a  href="{{ route($domain.'.pre-inventory-actions.assign',['pre-inventory-action'=>$resource->id]) }}"
                    class="btn btn-a btn-default ml-auto cursor-pointer text-white bg-primary"
                    title="Assign">
                    分配仓库
                </a>
            </div>
        @endif
    </div>

    @foreach ($resource->orders as $order)
        <h3 class="text-80 py-3 ml-3">明细 ID{{$order->id}}</h3>
        <div class="card p-6 w-full">
            <div class="flex text-80">
                <div class="w-1/5 py-6 px-8">
                    接收仓库
                </div>
                <div class="py-6 px-8">
                    {{$order->warehouse->name}}
                </div>
            </div>
            <div class="flex border-b border-40 text-80">
                <div class="w-1/5 py-6 px-8">
                    入库单明细
                </div>
                <div class="w-4/5 py-6 px-8">
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
                <div class="w-4/5 py-6 px-8">
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
@endsection
