<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" dir="{{ __('hymer::generic.is_rtl') == 'true' ? 'rtl' : 'ltr' }}">
<head>
    <title>@yield('page_title', setting('admin.title') . " - " . setting('admin.description'))</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="assets-path" content="{{ route('hymer.hymer_assets') }}"/>

    <!-- Favicon -->
    <?php $admin_favicon = Hymer::setting('admin.icon_image', ''); ?>
    @if($admin_favicon == '')
        <link rel="shortcut icon" href="{{ hymer_asset('images/logo-icon.png') }}" type="image/png">
    @else
        <link rel="shortcut icon" href="{{ Hymer::image($admin_favicon) }}" type="image/png">
    @endif

    <!-- Perfect Scrollbar -->
    <link rel="stylesheet" href="{{ hymer_asset('libs/perfect-scrollbar-1.5.5/css/perfect-scrollbar.css') }}">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ hymer_asset('libs/bootstrap-5.3.2/css/bootstrap.min.css') }}">

    <!-- Bootstrap Icon -->
    <link rel="stylesheet" href="{{ hymer_asset('libs/bootstrap-icons-1.11.3/font/bootstrap-icons.min.css') }}">

    <!-- Data Tables -->
    <link rel="stylesheet" href="{{ hymer_asset('libs/data-tables-2.0.0/css/dataTables.bootstrap5.min.css') }}">

    <!-- Select2 -->
    <link rel="stylesheet" href="{{ hymer_asset('libs/select2-4.1.0/css/select2.min.css') }}">

    <!-- Datetime Picker -->
    <link rel="stylesheet" href="{{ hymer_asset('libs/tempus-dominus-6.9.5/css/tempus-dominus.min.css') }}">

    <!-- Toastr -->
    <link rel="stylesheet" href="{{ hymer_asset('libs/toastr-2.1.1/toastr.min.css') }}">

    <!-- Dropzone -->
    <link rel="stylesheet" href="{{ hymer_asset('libs/dropzone-5.9.3/dropzone.min.css') }}">

    <!-- Nestable2 -->
    <link rel="stylesheet" href="{{ hymer_asset('libs/nestable-1.6.0/jquery.nestable.min.css') }}">

    <!-- CropperJs -->
    <link rel="stylesheet" href="{{ hymer_asset('libs/cropperjs-1.6.1/cropper.min.css') }}">

    <!-- EasyMDE -->
    <link rel="stylesheet" href="{{ hymer_asset('libs/easymde-2.18.0/css/easymde.css') }}">

    <!-- Font CSS -->
    <link rel="stylesheet" href="{{ hymer_asset('css/font.css') }}">

    <!-- App CSS -->
    <link rel="stylesheet" href="{{ hymer_asset('css/variable.css') }}">
    <link rel="stylesheet" href="{{ hymer_asset('css/app.css') }}">

    @yield('css')
    @if(__('hymer::generic.is_rtl') == 'true')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-rtl/3.4.0/css/bootstrap-rtl.css">
        <link rel="stylesheet" href="{{ hymer_asset('css/rtl.css') }}">
    @endif

    <!-- Few Dynamic Styles -->
    <style>
        .hymer .side-menu .navbar-header {
            background:{{ config('hymer.primary_color','#22A7F0') }};
            border-color:{{ config('hymer.primary_color','#22A7F0') }};
        }
        .widget .btn-primary{
            border-color:{{ config('hymer.primary_color','#22A7F0') }};
        }
        .widget .btn-primary:focus,
        .widget .btn-primary:hover,
        .widget .btn-primary:active,
        .widget .btn-primary.active,
        .widget .btn-primary:active:focus {
            background:{{ config('hymer.primary_color','#22A7F0') }};
        }
        .hymer .breadcrumb a{
            color:{{ config('hymer.primary_color','#22A7F0') }};
        }
    </style>

    @if(!empty(config('hymer.additional_css')))<!-- Additional CSS -->
        @foreach(config('hymer.additional_css') as $css)<link rel="stylesheet" type="text/css" href="{{ asset($css) }}">@endforeach
    @endif

    @yield('head')
</head>

<body class="hymer @if(isset($dataType) && isset($dataType->slug)){{ $dataType->slug }}@endif">

<div id="hymer-loader">
    <?php $admin_loader_img = Hymer::setting('admin.loader', ''); ?>
    @if($admin_loader_img == '')
        <img src="{{ hymer_asset('images/logo-icon.png') }}" alt="Hymer Loader">
    @else
        <img src="{{ Hymer::image($admin_loader_img) }}" alt="Hymer Loader">
    @endif
</div>

<?php
if (Str::startsWith(Auth::user()->avatar, 'http://') || Str::startsWith(Auth::user()->avatar, 'https://')) {
    $user_avatar = Auth::user()->avatar;
} else {
    $user_avatar = Hymer::image(Auth::user()->avatar);
}
?>

<div class="app-container">
    <div class="d-block d-sm-none"></div>
    <div class="content-container">
        @include('hymer::dashboard.navbar')
        @include('hymer::dashboard.sidebar')
        <script>
            (function(){
                    let appContainer = document.querySelector('.app-container'),
                        sidebar = appContainer.querySelector('.side-menu'),
                        navbar = appContainer.querySelector('nav.navbar.fixed-top'),
                        loader = document.getElementById('hymer-loader'),
                        hamburgerMenu = document.querySelector('.hamburger'),
                        sidebarTransition = sidebar.style.transition,
                        navbarTransition = navbar.style.transition,
                        containerTransition = appContainer.style.transition;

                    sidebar.style.WebkitTransition = sidebar.style.MozTransition = sidebar.style.transition =
                    appContainer.style.WebkitTransition = appContainer.style.MozTransition = appContainer.style.transition =
                    navbar.style.WebkitTransition = navbar.style.MozTransition = navbar.style.transition = 'none';

                    if (window.innerWidth > 768 && window.localStorage && window.localStorage['hymer.stickySidebar'] == 'true') {
                        appContainer.className += ' expanded no-animation';
                        loader.style.left = (sidebar.clientWidth/2)+'px';
                        hamburgerMenu.className += ' is-active no-animation';
                    }

                   navbar.style.WebkitTransition = navbar.style.MozTransition = navbar.style.transition = navbarTransition;
                   sidebar.style.WebkitTransition = sidebar.style.MozTransition = sidebar.style.transition = sidebarTransition;
                   appContainer.style.WebkitTransition = appContainer.style.MozTransition = appContainer.style.transition = containerTransition;
            })();
        </script>
        <!-- Main Content -->
        <div class="container-fluid">
            <div class="side-body padding-top px-4">
                @yield('page_header')
                <div id="hymer-notifications"></div>
                @yield('content')
            </div>
        </div>
    </div>
</div>
@include('hymer::partials.app-footer')

<script type="text/x-template" id="admin-menu-template">
    @include('hymer::components.admin-menu')
</script>

<!-- Javascript Libs -->

<!-- jQuery -->
<script src="{{ hymer_asset('libs/jquery-3.7.1/jquery.min.js') }}"></script>

<!-- Bootstrap -->
<script src="{{ hymer_asset('libs/bootstrap-5.3.2/js/bootstrap.bundle.js') }}"></script>

<!-- jQuery Match Height -->
<script src="{{ hymer_asset('libs/jquery-match-height-0.7.2/jquery.matchHeight.min.js') }}"></script>

<!-- Data Table -->
<script src="{{ hymer_asset('libs/data-tables-2.0.0/js/dataTables.min.js') }}"></script>
<script src="{{ hymer_asset('libs/data-tables-2.0.0/js/dataTables.bootstrap5.min.js') }}"></script>

<!-- Perfect Scrollbar -->
<script src="{{ hymer_asset('libs/perfect-scrollbar-1.5.5/js/perfect-scrollbar.min.js') }}"></script>

<!-- Select2 -->
<script src="{{ hymer_asset('libs/select2-4.1.0/js/select2.min.js') }}"></script>

<!-- Datetime Picker -->
<script src="{{ hymer_asset('libs/tempus-dominus-6.9.5/js/tempus-dominus.min.js') }}"></script>

<!-- Toastr -->
<script src="{{ hymer_asset('libs/toastr-2.1.1/toastr.min.js') }}"></script>

<!-- Dropzone -->
<script src="{{ hymer_asset('libs/dropzone-5.9.3/dropzone.min.js') }}"></script>

<!-- Nestable2 -->
<script src="{{ hymer_asset('libs/nestable-1.6.0/jquery.nestable.min.js') }}"></script>

<!-- CropperJs -->
<script src="{{ hymer_asset('libs/cropperjs-1.6.1/cropper.min.js') }}"></script>

<!-- Ace Editor -->
<script src="{{ hymer_asset('libs/ace-1.32.6/ace.js') }}"></script>
<script src="{{ hymer_asset('libs/ace-1.32.6/mode-json.js') }}"></script>

<!-- Tinymce -->
<script src="{{ hymer_asset('libs/tinymce-6.8.3/tinymce.min.js') }}"></script>

<!-- EasyMDE -->
<script src="{{ hymer_asset('libs/easymde-2.18.0/js/easymde.js') }}"></script>

<!-- Multilingual -->
<script src="{{ hymer_asset('js/multilingual.js') }}"></script>

<!-- VueJs -->
<script src="{{ hymer_asset('libs/vue-3.3.4/vue.global.js') }}"></script>

@include('hymer::media.manager')

<script>
    window.vue = Vue.createApp({})
</script>
<script type="module">
    window.vue.component('admin-menu', {
        template: '#admin-menu-template',
        props: {
            items: {
                type: Array,
                default: [],
            }
        },
        methods: {
            classes: function(item) {
                let classes = [];
                if (item.children.length > 0) {
                    classes.push('dropdown');
                }
                if (item.active) {
                    classes.push('active');
                }


                return classes.join(' ');
            },
            color: function(item) {
                if (item.color && item.color != '#000000') {
                    return item.color;
                }

                return '';
            }
        }
    })

    let admin_menu = window.vue.mount('#admin-menu')
</script>

<script src="{{ hymer_asset('js/hymer-ace-editor.js') }}"></script>
<script src="{{ hymer_asset('js/helper.js') }}"></script>
<script src="{{ hymer_asset('js/app.js') }}"></script>

<script>
    @if(Session::has('alerts'))
        let alerts = {!! json_encode(Session::get('alerts')) !!};
        window.helpers.displayAlerts(alerts, toastr);
    @endif

    @if(Session::has('message'))

    // TODO: change Controllers to use AlertsMessages trait... then remove this
    var alertType = {!! json_encode(Session::get('alert-type', 'info')) !!};
    var alertMessage = {!! json_encode(Session::get('message')) !!};
    var alerter = toastr[alertType];

    if (alerter) {
        alerter(alertMessage);
    } else {
        toastr.error("toastr alert-type " + alertType + " is unknown");
    }
    @endif
</script>
@yield('javascript')
@stack('javascript')
@if(!empty(config('hymer.additional_js')))<!-- Additional Javascript -->
    @foreach(config('hymer.additional_js') as $js)<script src="{{ asset($js) }}"></script>@endforeach
@endif

</body>
</html>
