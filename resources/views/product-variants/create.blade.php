@extends('layouts.app')

@section('content')
    <card-title label-name="创建变体"></card-title>
    <div class="card w-full">
        <product-variant-form
                :via-relation-resource='@json($product)'
                resource-name="product-variants"
                via-relation-name="{{$viaRelationName}}"
                via-relation-id="{{$viaRelationId}}"
        ></product-variant-form>
    </div>
@endsection
