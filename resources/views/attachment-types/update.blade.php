@extends('layouts.app')

@section('content')
    <card-title label-name="编辑附加费用类型"></card-title>
    <div class="card w-full">
        <attachment-type-form
                resource-name="attachment-types"
                :resource='@json($resource)'
                resource-id="{{$resource->id}}"
        ></attachment-type-form>
    </div>
@endsection
