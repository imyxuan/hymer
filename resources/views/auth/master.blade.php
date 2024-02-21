<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" dir="{{ __('hymer::generic.is_rtl') == 'true' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="none" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="admin login">
    <title>@yield('title', 'Admin - '.Hymer::setting("admin.title"))</title>
{{--    <link rel="stylesheet" href="{{ hymer_asset('libs/bootstrap-3.4.1/css/bootstrap.min.css') }}">--}}
    <link rel="stylesheet" href="{{ hymer_asset('libs/bootstrap-5.3.2/css/bootstrap.min.css') }}&c={{ time() }}">
    <link rel="stylesheet" href="{{ hymer_asset('libs/animate-4.1.1/animate.min.css') }}&c={{ time() }}">
    <link rel="stylesheet" href="{{ hymer_asset('css/app.css') }}">
    @if (__('hymer::generic.is_rtl') == 'true')
        <link rel="stylesheet" href="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/5.3.1/css/bootstrap.rtl.min.css">
{{--        TODO--}}
        <link rel="stylesheet" href="{{ hymer_asset('css/rtl.css') }}">
    @endif
    <style>
        body {
            background-image:url('{{ Hymer::image( Hymer::setting("admin.bg_image"), hymer_asset("images/bg.jpg") ) }}');
            background-color: {{ Hymer::setting("admin.bg_color", "#FFFFFF" ) }};
        }
        body.login .login-sidebar {
            border-top:5px solid {{ config('hymer.primary_color','#22A7F0') }};
            margin-bottom: 0;
        }
        @media (max-width: 767px) {
            body.login .login-sidebar {
                border-top:0px !important;
                border-left:5px solid {{ config('hymer.primary_color','#22A7F0') }};
            }
        }
        body.login .form-group.focused{
            border-color:{{ config('hymer.primary_color','#22A7F0') }};
        }
        .login-button, .bar:before, .bar:after{
            background:{{ config('hymer.primary_color','#22A7F0') }};
        }
        .remember-me-text{
            padding:0 5px;
        }
    </style>

    @yield('css')
</head>
<body class="login">
<div class="container-fluid">
    <div class="row">
        <div class="faded-bg animate__animated"></div>
        <div class="d-none d-sm-block col-sm-7 col-md-8">
            <div class="clearfix">
                <div class="col-sm-12 col-md-10 md-offset-2">
                    <div class="logo-title-container d-flex align-items-center">
                        <?php $admin_logo_img = Hymer::setting('admin.icon_image', ''); ?>
                        @if($admin_logo_img == '')
                            <img class="flip logo d-none d-sm-block animate__animated animate__fadeIn" src="{{ hymer_asset('images/logo-icon-light.png') }}" alt="Logo Icon">
                        @else
                            <img class="flip logo d-none d-sm-block animate__animated animate__fadeIn" src="{{ Hymer::image($admin_logo_img) }}" alt="Logo Icon">
                        @endif
                        <div class="copy animate__animated animate__fadeIn">
                            <h1>{{ Hymer::setting('admin.title', 'Hymer') }}</h1>
                            <p>{{ Hymer::setting('admin.description', __('hymer::login.welcome')) }}</p>
                        </div>
                    </div> <!-- .logo-title-container -->
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-5 col-md-4 login-sidebar">

           @yield('content')

        </div> <!-- .login-sidebar -->
    </div> <!-- .row -->
</div> <!-- .container-fluid -->
@yield('script')
</body>
</html>
