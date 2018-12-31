@extends('layouts.app')

@section('content')
    <pre-inventory-action-order-shipment :resource='@json($resource)'
                                         :order='@json($order)'
                                         :logistic='@json($logistic)'
                                         post-api="{{route('supplier.supplies.order.shipment.store',['supply'=>$resource->id,'order'=>$order->id])}}"
    ></pre-inventory-action-order-shipment>
@endsection
