<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
    <style>
        body {
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 400px;
            background: #0a146e;
            background-image: url({{ asset('assets/images/pattern.svg') }});
            background-size: cover;
            z-index: -1;
        }

        .main {
            padding-top: 8rem;
        }

        .main-title {
            margin-bottom: 0.7rem;
            color: white;
            font-size: 3rem;
        }

        .subtitle {
            margin-bottom: 2.5rem;
            color: white
        }

        .wrapper {
            border: 1px solid #ccc;
            padding: 3rem 2rem;
            border-radius: 0.4rem;
            background-color: #fff;
        }
    </style>
    @yield('css')
</head>

<body>
    <div class="main container">
        <h1 class="main-title text-center">Currency Converter</h1>
        <h3 class="subtitle text-center">Check live foreign currency exchange rates</h3>
        <div class="wrapper">
            @yield('content')
        </div>
    </div>

    @yield('script')

</body>

</html>
