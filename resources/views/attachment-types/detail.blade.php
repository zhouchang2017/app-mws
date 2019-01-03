@extends('layouts.app')

@section('content')
    <resource-detail-header
            label-name="附加费用类型详情"
            resource-name="attachment-types"
            resource-id="{{$resource->id}}"
            :can-destroy="true"
            :can-update="true"
    ></resource-detail-header>

    <div class="form-list mb-6">
        <form-item title="附加费用类型名称" value="{{$resource->name}}"></form-item>
        <form-item title="附加费用类型调整比率" value="{{$resource->rate}}"></form-item>
        <form-item title="最后更新时间" value="{{$resource->updated_at}}"></form-item>
    </div>
@endsection