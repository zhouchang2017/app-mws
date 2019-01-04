@extends('layouts.app')

@section('content')
    <card-title label="{{$singularLabel}}"></card-title>
    <div class="card w-full">
        <product-form :resource='@json($resource)' resource-id="{{$resource->id}}"></product-form>
    </div>
@endsection
