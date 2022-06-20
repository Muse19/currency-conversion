@extends('layouts.main')

@section('title', '404 - Page Not Found')

@section('css')
    <style>
        h1 {
            color: #ee404c
        }

        h2 {
            margin-bottom: 2rem;
        }

        .action-wrapper {
            text-align: center;
        }
    </style>
@endsection

@section('content')

    <h1 class="text-center">
        <img src="{{ asset('assets/images/error-404.png') }}" alt="404-icon">
    </h1>
    <h2 class="text-center">Page not found</h2>
    <div class="action-wrapper">
        <a class="btn btn-error" href="/">Go to home</a>
    </div>

@endsection
