@extends('layouts.app')

@section('content')

    <resource-detail-header
            label-name="入库计划详情"
            resource-name="supplies"
            resource-id="{{$resource->id}}"
    ></resource-detail-header>
    <div class="form-list mb-6">
        <form-item title="计划说明" value="{{$resource->description}}"></form-item>
        <form-item title="当前状态" value="{{$resource->current_state}}"></form-item>
        <form-item title="运输方式" value="{{$resource->has_ship ? '物流/快递' : '无需物流' }}"></form-item>
        <form-item title="更新时间" value="{{$resource->updated_at }}"></form-item>
    </div>

    <card-title label-name="计划来源供应商"></card-title>
    <div class="form-list mb-6">
        <form-item title="名称" value="{{$resource->origin->name}}"></form-item>
        <form-item title="编码" value="{{$resource->origin->code}}"></form-item>
        <form-item title="运输方式" value="{{$resource->has_ship ? '物流/快递' : '无需物流' }}"></form-item>
        <form-item title="更新时间" value="{{$resource->updated_at }}"></form-item>
    </div>


    <card-title label-name="状态记录"></card-title>
    @component('components.statuses',['statuses'=>$resource->statuses])
    @endcomponent

    <card-title label-name="入库商品列表"></card-title>

    <div class="card w-full">
        <div class="p-6">
            <product-variant-list :items='@json($resource->items)'></product-variant-list>
        </div>
        {{--已提交，显示审核按钮--}}
        @if ($resource->state->name === \App\Models\Supply::PENDING)
            <div class="bg-30 flex px-8 py-4">
                <form class="ml-auto" action="{{ route($domain.'.supplies.approved',['supply'=>$resource->id]) }}" method="POST"
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
    </div>
@endsection
