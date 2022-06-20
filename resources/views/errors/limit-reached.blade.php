@extends('layouts.main')

@section('title', 'Limit Reached')

@section('css')
    <style>
        h1 {
            color: #ee404c
        }

        .main-text {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .main-text img {
            width: 80px;
            height: 80px;
        }

        .action-wrapper {
            text-align: center;
            margin-top: 2rem;
        }
    </style>
@endsection

@section('content')

    <div class="main-text">
        <img src="{{ asset('assets/images/alert.png') }}" alt="alert-icon">
        <h1 class="text-center">
            Oops! You have reached the limit of allowed requests.
        </h1>
    </div>

    <h2 class="text-center">Please try again within 24h.</h2>

    <div class="action-wrapper">
        <a class="btn btn-error" href="/">Go to home</a>
    </div>

@endsection
