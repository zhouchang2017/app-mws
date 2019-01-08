@extends('layouts.app')

@section('content')

    <card-title label="分配仓库"></card-title>
    <div class="card w-full">
        <assign-warehouse-form
                data-path="origin.items"
                uri-key="pre-inventory-actions"
                resource-id="{{$resource->id}}"
        ></assign-warehouse-form>
    </div>
@endsection
