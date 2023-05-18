<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<style>
    body{
        background-image: url("{{ asset('images/bg_login.jpg') }}") !important;
        background-repeat: no-repeat;
        background-size: cover;
    }
    .card {
    background-color: #8f2b3547;
    }
    .form-control {
    background-color: #ffffff38;
}
.form-control:focus {
    color: white;
    background-color: #ffffff38;
    border-color: #fcfcfc;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
}
.col-form-label {
    color: white;
    font-weight: bolder;
}
.btn-primary {
    color: white;
    background-color: #ff0000;
    border-color: #ff0018;
    width: 100%;
}
.card-header{
    font-size: 40px;
    font-weight: bold;
    color: black;
}
.form-control {
    display: block;
    width: 100%;
    height: calc(1.6em + 0.75rem + 2px);
    padding: 0.375rem 0.75rem;
    font-size: 0.9rem;
    font-weight: 400;
    line-height: 1.6;
    color: white;
}

.btn-grad {    background-image: linear-gradient(to right, #ef1433 0%, #ef4944 51%, #d73d2a 100%);
}
.btn-grad {
    margin: 1px;
    padding: 7px 32px;
    text-align: center;
    text-transform: uppercase;
    transition: 0.5s;
    background-size: 200% auto;
    color: white;
    /* box-shadow: 0 0 20px #eee; */
    border-radius: 10px;
    display: block;
}

          .btn-grad:hover {
            background-position: right center; /* change the direction of the change here */
            color: #fff;
            text-decoration: none;
          }
         
</style>

<body>
    <div id="app">
        

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>