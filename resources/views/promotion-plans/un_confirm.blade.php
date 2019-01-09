@extends('layouts.app')

@section('content')


    <div class="card mb-6">
        <div class="p-6">
            <card-title class="justify-center" label="促销活动(促销计划)邀请"></card-title>
            <div class="w-full">
                <h3 class="font-sans font-thin mb-6">{{$resource->supplier->name}}:</h3>

                {{--<p class="text-grey mb-3">我们诚邀您参加以下促销活动</p>--}}

                @if(!is_null($resource->promotion->image))
                    <div class="flex items-center justify-center w-full">
                        <img class="rounded block" style="max-height: 280px;" src="{{$resource->promotion->image}}"
                             alt=""/>
                    </div>
                @endif

                <h2 class="font-sans font-thin leading-normal mb-4">
                    {{$resource->promotion->name}}
                    <span class="float-right bg-grey-lighter rounded-lg px-3 py-1 text-xs font-semibold text-grey-darker">{{$resource->promotion->type_name}}</span>
                </h2>

                <p class="text-grey-darkest mb-6 leading-tight">
                    {{$resource->promotion->description}}
                </p>

                <p class="text-xs font-semibold text-70">促销计划明细</p>
                <div class="border-30 border-b mb-3"></div>

                <el-table
                        :data='@json($resource->promotionVariants)'
                >
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
                            prop="began_at"
                            label="开始时间"
                    ></el-table-column>
                    <el-table-column
                            prop="ended_at"
                            label="结束时间"
                    ></el-table-column>
                </el-table>

            </div>
        </div>

        <div class="bg-30 flex px-8 py-4">
            <div class="ml-auto"
            >
                <form
                        method="POST"
                        action="{{route($domain.'.promotion-plans.confirm',['promotionPlan'=>$resource])}}"
                >
                    @method('PATCH')
                    @csrf
                    <button type="submit"
                            class="btn btn-default btn-primary inline-flex items-center relative">
                    <span class="">
                        确认参加
                    </span>
                    </button>
                </form>
            </div>
        </div>

    </div>


@endsection