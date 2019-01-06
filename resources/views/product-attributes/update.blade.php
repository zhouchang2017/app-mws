@extends('layouts.app')

@section('content')
    <card-title label="编辑{{$singularLabel}}"></card-title>
    <div class="card w-full">
        <product-attribute-form
                resource-id="{{$resource->id}}"
                :resource='@json($resource)'
                uri-key="{{$uriKey}}"
                label="{{$label}}"
                singular-label="{{$singularLabel}}"
        ></product-attribute-form>
    </div>
@endsection
