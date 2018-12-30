@extends('layouts.app')

@section('content')

    <card-title label-name="分配仓库"></card-title>
    <div class="card w-full">
        <assign-warehouse-form data-path="origin.items" resource-name="pre-inventory-actions" resource-id="{{$resource->id}}"></assign-warehouse-form>
    </div>
@endsection
