@extends('layouts.app')

@section('content')
    <card-title label="创建{{$singularLabel}}"></card-title>
    <div class="card w-full">
        <promotion-plan-form
                via-relation-name="{{request()->viaRelationName}}"
                via-relation-id="{{request()->viaRelationId}}"
                uri-key="{{$uriKey}}"
                label="{{$label}}"
                singular-label="{{$singularLabel}}"
        ></promotion-plan-form>
    </div>
@endsection
