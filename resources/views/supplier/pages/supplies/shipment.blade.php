@extends('layouts.app')

@section('content')
    <supply-shipment :resource='@json($resource)'
                                         :order='@json($order)'
                                         :logistic='@json($logistic)'
                                         post-api="{{route('supplier.supplies.order.shipment.store',['supply'=>$resource->id,'order'=>$order->id])}}"
    ></supply-shipment>
@endsection
