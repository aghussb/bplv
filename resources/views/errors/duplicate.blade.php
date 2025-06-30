@extends('errors.minimal')

{{--@section('title', @$title ?? '')--}}
{{--@section('code', '404')--}}
{{--@section('message', @$message ?? '')--}}

@section('title')
    {{ $title ?? '' }}
@endsection

@section('code')
    {{ $code ?? '' }}
@endsection

@section('message')
    {{ @$message ?? '' }}
@endsection