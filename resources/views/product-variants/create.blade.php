@extends('layouts.app')

@section('content')
    <card-title label="创建变体"></card-title>
    <div class="card w-full">
        <product-variant-form
                :via-relation-resource='@json($product)'
                via-relation-name="{{$viaRelationName}}"
                via-relation-id="{{$viaRelationId}}"
                uri-key="{{$uriKey}}"
                label="{{$label}}"
                singular-label="{{$singularLabel}}"
        ></product-variant-form>
    </div>
@endsection
