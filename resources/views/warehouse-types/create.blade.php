@extends('layouts.app')

@section('content')
    <card-title label="创建产品属性"></card-title>
    <div class="card w-full">
        <warehouse-type-form
                uri-key="{{$uriKey}}"
                label="{{$label}}"
                singular-label="{{$singularLabel}}"
        ></warehouse-type-form>
    </div>
@endsection
