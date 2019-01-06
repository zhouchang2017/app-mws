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
        <translation-detail-item title="名称" attribute="name" :translations='@json($resource->translations)'></translation-detail-item>
        <form-item title="编码" value="{{$resource->code}}"></form-item>
        <form-item title="所属分类" value="{{$resource->taxon->name }}" uri-key="taxons" resource-id="{{$resource->taxon->id}}"></form-item>
        <form-item title="最后更新时间" value="{{$resource->updated_at}}"></form-item>
    </div>
@endsection