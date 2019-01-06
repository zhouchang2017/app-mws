@extends('layouts.app')

@section('content')
    <card-title label="站内消息"></card-title>
    <notification :types='@json($notifyTypes)'></notification>
@endsection
