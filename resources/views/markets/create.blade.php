@extends('layouts.app')

@section('content')
    <card-title label="创建{{$singularLabel}}"></card-title>
    <div class="card w-full">
        <market-form
                uri-key="{{$uriKey}}"
                label="{{$label}}"
                singular-label="{{$singularLabel}}"
        ></market-form>
    </div>
@endsection
