@extends('errors::minimal')

@section('title', __('Unauthorized'))
@section('code', '401')
@section('message')
    <h3>Please contact your administrator.</h3>
    <p>You are unauthorized to perform this action</p>
    <p>{{ $message ?? '' }}</p>
    <br>
@stop