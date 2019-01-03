@extends('layouts.app')

@section('content')
    <card-title label-name="创建附加费用类型"></card-title>
    <div class="card w-full">
        <attachment-type-form resource-name="attachment-types"></attachment-type-form>
    </div>
@endsection
