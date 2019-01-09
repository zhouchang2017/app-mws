@extends('layouts.app')

@section('content')
    <pre-inventory-action-order-shipment :resource='@json($resource)'
                                         :logistic='@json($logistic)'
                                         post-api="{{route('admin.pre-inventory-action-orders.shipment.create',['pre-inventory-action-order'=>$resource->id])}}"
    ></pre-inventory-action-order-shipment>
@endsection
