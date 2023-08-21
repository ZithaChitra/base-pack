@extends('errors::minimal')

@section('title', __('Not Found'))
@section('code', '404')
@section('message')
    <p>You currently do not have any contexts on this app.</p>
    <h3>Please contact your administrator.</h3>
    <br>
@stop