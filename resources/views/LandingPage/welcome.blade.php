@extends('layouts.app')

@section('title', 'Trang chủ')
@section('nav-mode', 'dynamic')

@section('content')
    @include('components.home.hero')
    @include('components.home.about')
    @include('components.home.services')
    @include('components.home.review')
@endsection

