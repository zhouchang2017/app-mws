@extends('layouts.app')

@section('content')
    <card-title label="编辑{{$singularLabel}}"></card-title>
    <div class="card w-full">
        <warehouse-form
                resource-id="{{$resource->id}}"
                :resource='@json($resource)'
                uri-key="{{$uriKey}}"
                label="{{$label}}"
                singular-label="{{$singularLabel}}"
        ></warehouse-form>
    </div>
@endsection
