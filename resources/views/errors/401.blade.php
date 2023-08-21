@extends('errors::minimal')

@section('title', __('Unauthorized'))
@section('code', '401')
@section('message')
    <p>You are unauthorized to perform this action</p>
    <h3>Please contact your administrator.</h3>
    <br>
@stop