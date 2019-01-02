@extends('layouts.app')

@section('content')
    <card-title label-name="编辑产品"></card-title>
    <div class="card w-full">
        <product-form :resource='@json($resource)' resource-id="{{$resource->id}}"></product-form>
    </div>
@endsection
