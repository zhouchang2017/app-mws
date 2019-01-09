@extends('layouts.app')

@section('content')
    <card-title label="创建{{$singularLabel}}"></card-title>
    <div class="card w-full">
        <supplier-user-form
                resource-id="{{$resource->id}}"
                :resource='@json($resource)'
                uri-key="{{$uriKey}}"
                label="{{$label}}"
                singular-label="{{$singularLabel}}"
        ></supplier-user-form>
    </div>
@endsection
