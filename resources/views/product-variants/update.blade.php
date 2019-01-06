@extends('layouts.app')

@section('content')
    <card-title label="编辑{{$singularLabel}}"></card-title>
    <div class="card w-full">
        <product-variant-form
                resource-id="{{$resource->id}}"
                :resource='@json($resource)'
                via-relation-name="{{$viaRelationName}}"
                via-relation-id="{{$viaRelationId}}"
                uri-key="{{$uriKey}}"
                label="{{$label}}"
                singular-label="{{$singularLabel}}"
        ></product-variant-form>
    </div>
@endsection
