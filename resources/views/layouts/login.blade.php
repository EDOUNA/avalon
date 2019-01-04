<!DOCTYPE html>
<html>
<head>
    <base href="{{ url('') }}/">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow, noarchive, noodp, NoImageIndex, noydir">
    <title>{{ env('APP_NAME') }}</title>
    <meta name="mobile-web-app-capable" content="yes">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <link href="{{ asset('lib/bs/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('lib/fa/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('css/daterangepicker.css') }}" rel="stylesheet" type="text/css"/>

    <link href="{{ asset('lib/adminlte/css/AdminLTE.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('lib/adminlte/css/skins/skin-blue-light.min.css') }}" rel="stylesheet" type="text/css"/>

    <!--[if lt IE 9]>
    <script src="{{ asset('js/lib/html5shiv.min.js') }}"></script>
    <script src="{{ asset('js/lib/respond.min.js') }}"></script>
    <![endif]-->

    <script src="{{ asset('js/lib/jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/lib/jquery-ui.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/lib/moment.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/ff/moment/en_US.js') }}" type="text/javascript"></script>

    <script src="{{ asset('js/lib/daterangepicker.js') }}" type="text/javascript"></script>
    <script src="{{ asset('lib/adminlte/js/adminlte.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/lib/chart.js') }}" type="text/javascript"></script>
</head>
<body class="login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="{{ url('/') }}">
            <stron>{{ env('APP_NAME') }}</stron>
        </a>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <p class="well">
                Welkom op de Avalon app!<br/>
            </p>
        </div>
    </div>


    <div class="login-box-body">
        @yield('content')
    </div>
</div>
</body>
</html>
