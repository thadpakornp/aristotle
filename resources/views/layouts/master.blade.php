<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Title Page-->
    <title>Aristotle</title>

    <!-- Fontfaces CSS-->
    <link href="{{ asset('css/font-face.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('vendor/font-awesome-4.7/css/font-awesome.min.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('vendor/font-awesome-5/css/fontawesome-all.min.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('vendor/mdi-font/css/material-design-iconic-font.min.css') }}" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="{{ asset('vendor/bootstrap-4.1/bootstrap.min.css') }}" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="{{ asset('vendor/animsition/animsition.min.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css') }}" rel="stylesheet"
          media="all">
    <link href="{{ asset('vendor/wow/animate.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('vendor/css-hamburgers/hamburgers.min.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('vendor/slick/slick.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('vendor/select2/select2.min.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('vendor/perfect-scrollbar/perfect-scrollbar.css') }}" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="{{ asset('css/theme.css') }}" rel="stylesheet" media="all">

    <link rel="stylesheet" type="text/css" href="{{ asset('css/modal-loading.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/modal-loading-animate.css') }}"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.10.1/sweetalert2.min.css"
          integrity="sha512-zEmgzrofH7rifnTAgSqWXGWF8rux/+gbtEQ1OJYYW57J1eEQDjppSv7oByOdvSJfo0H39LxmCyQTLOYFOa8wig=="
          crossorigin="anonymous"/>
    @yield('style')
</head>

<body class="animsition">
<div class="page-wrapper">
    <!-- HEADER MOBILE-->
    <header class="header-mobile d-block d-lg-none">
        <div class="header-mobile__bar">
            <div class="container-fluid">
                <div class="header-mobile-inner">
                    <a class="logo" href="#">
                        <img src="{{ asset('images/icon/logo.png') }}" alt=""/>
                    </a>
                    <button class="hamburger hamburger--slider" type="button">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                    </button>
                </div>
            </div>
        </div>
        <nav class="navbar-mobile">
            <div class="container-fluid">
                <ul class="navbar-mobile__list list-unstyled">
                    @role('admin')
                    <li class="has-sub">
                        <a class="js-arrow" href="#">
                            <i class="fas fa-user"></i>Authentication</a>
                        <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                            <li>
                                <a href="{{ route('laravelroles::roles.index') }}">Rules</a>
                            </li>
                            <li>
                                <a href="{{ route('backend.users.index') }}">Users</a>
                            </li>
                        </ul>
                    </li>
                    @endrole
                </ul>
            </div>
        </nav>
    </header>
    <!-- END HEADER MOBILE-->

    <!-- MENU SIDEBAR-->
    <aside class="menu-sidebar d-none d-lg-block">
        <div class="logo">
            <a href="#">
                <img src="{{ asset('images/icon/logo.png') }}" alt="Cool Admin"/>
            </a>
        </div>
        <div class="menu-sidebar__content js-scrollbar1">
            <nav class="navbar-sidebar">
                <ul class="list-unstyled navbar__list">
                    @role('admin')
                    <li class="has-sub">
                        <a class="js-arrow" href="#">
                            <i class="fas fa-user"></i>Authentication</a>
                        <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                            <li>
                                <a href="{{ route('laravelroles::roles.index') }}">Rules</a>
                            </li>
                            <li>
                                <a href="{{ route('backend.users.index') }}">Users</a>
                            </li>
                        </ul>
                    </li>
                    @endrole
                </ul>
            </nav>
        </div>
    </aside>
    <!-- END MENU SIDEBAR-->

    <!-- PAGE CONTAINER-->
    <div class="page-container">
        <!-- HEADER DESKTOP-->
        <header class="header-desktop">
            <div class="section__content section__content--p30">
                <div class="container-fluid">
                    <div class="header-wrap">
                        <div class="form-header">
                        </div>
                        <div class="header-button">
                            <div class="noti-wrap">
                                {{--                                <div class="noti__item js-item-menu">--}}
                                {{--                                    <i class="zmdi zmdi-notifications"></i>--}}
                                {{--                                    <span class="quantity">3</span>--}}
                                {{--                                    <div class="notifi-dropdown js-dropdown">--}}
                                {{--                                        <div class="notifi__title">--}}
                                {{--                                            <p>You have 3 Notifications</p>--}}
                                {{--                                        </div>--}}
                                {{--                                        <div class="notifi__item">--}}
                                {{--                                            <div class="bg-c1 img-cir img-40">--}}
                                {{--                                                <i class="zmdi zmdi-email-open"></i>--}}
                                {{--                                            </div>--}}
                                {{--                                            <div class="content">--}}
                                {{--                                                <p>You got a email notification</p>--}}
                                {{--                                                <span class="date">April 12, 2018 06:50</span>--}}
                                {{--                                            </div>--}}
                                {{--                                        </div>--}}
                                {{--                                        <div class="notifi__item">--}}
                                {{--                                            <div class="bg-c2 img-cir img-40">--}}
                                {{--                                                <i class="zmdi zmdi-account-box"></i>--}}
                                {{--                                            </div>--}}
                                {{--                                            <div class="content">--}}
                                {{--                                                <p>Your account has been blocked</p>--}}
                                {{--                                                <span class="date">April 12, 2018 06:50</span>--}}
                                {{--                                            </div>--}}
                                {{--                                        </div>--}}
                                {{--                                        <div class="notifi__item">--}}
                                {{--                                            <div class="bg-c3 img-cir img-40">--}}
                                {{--                                                <i class="zmdi zmdi-file-text"></i>--}}
                                {{--                                            </div>--}}
                                {{--                                            <div class="content">--}}
                                {{--                                                <p>You got a new file</p>--}}
                                {{--                                                <span class="date">April 12, 2018 06:50</span>--}}
                                {{--                                            </div>--}}
                                {{--                                        </div>--}}
                                {{--                                        <div class="notifi__footer">--}}
                                {{--                                            <a href="#">All notifications</a>--}}
                                {{--                                        </div>--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}
                            </div>
                            <div class="account-wrap">
                                <div class="account-item clearfix js-item-menu">
                                    <div class="image">
                                        <img
                                            src="@if(auth()->user()->profile == null) {{ asset('images/icon/avatar-01.jpg') }} @else {{ asset(auth()->user()->profile) }} @endif"
                                            alt="{{ auth()->user()->name }} {{ auth()->user()->surname }}"/>
                                    </div>
                                    <div class="content">
                                        <a class="js-acc-btn"
                                           href="#">{{ auth()->user()->name }} {{ auth()->user()->surname }}</a>
                                    </div>
                                    <div class="account-dropdown js-dropdown">
                                        <div class="info clearfix">
                                            <div class="image">
                                                <a href="#">
                                                    <img
                                                        src="@if(auth()->user()->profile == null) {{ asset('images/icon/avatar-01.jpg') }} @else {{ asset(auth()->user()->profile) }} @endif"
                                                        alt="{{ auth()->user()->name }} {{ auth()->user()->surname }}"/>
                                                </a>
                                            </div>
                                            <div class="content">
                                                <h5 class="name">
                                                    <a href="#">{{ auth()->user()->name }} {{ auth()->user()->surname }}</a>
                                                </h5>
                                                <span class="email">{{ auth()->user()->email }}</span>
                                            </div>
                                        </div>
                                        <div class="account-dropdown__body">
                                            <div class="account-dropdown__item">
                                                <a href="#" class="text-danger">
                                                    <i class="zmdi zmdi-edit text-danger"></i>Create Channel</a>
                                            </div>
                                            <div class="account-dropdown__item">
                                                <a href="#">
                                                    <i class="zmdi zmdi-account"></i>Account</a>
                                            </div>
                                        </div>
                                        <div class="account-dropdown__footer">
                                            <a href="#">
                                                <i class="zmdi zmdi-power"></i>Logout</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- HEADER DESKTOP-->

        <!-- MAIN CONTENT-->
    @yield('content')
    <!-- END MAIN CONTENT-->
        <!-- END PAGE CONTAINER-->
    </div>

</div>

<!-- Jquery JS-->
<script src="{{ asset('vendor/jquery-3.2.1.min.js') }}"></script>
<!-- Bootstrap JS-->
<script src="{{ asset('vendor/bootstrap-4.1/popper.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap-4.1/bootstrap.min.js') }}"></script>
<!-- Vendor JS       -->
<script src="{{ asset('vendor/slick/slick.min.js') }}">
</script>
<script src="{{ asset('vendor/wow/wow.min.js') }}"></script>
<script src="{{ asset('vendor/animsition/animsition.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap-progressbar/bootstrap-progressbar.min.js') }}">
</script>
<script src="{{ asset('vendor/counter-up/jquery.waypoints.min.js') }}"></script>
<script src="{{ asset('vendor/counter-up/jquery.counterup.min.js') }}">
</script>
<script src="{{ asset('vendor/circle-progress/circle-progress.min.js') }}"></script>
<script src="{{ asset('vendor/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('vendor/chartjs/Chart.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/select2/select2.min.js') }}">
</script>

<script type="text/javascript" src="{{ asset('js/modal-loading.js') }}"></script>

<!-- Main JS-->
<script src="{{ asset('js/main.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.10.1/sweetalert2.min.js"
        integrity="sha512-geFV99KIlNElg1jwzHE6caxE2tLBEw5l+e2cTRPJz273bbiQqpEuqvQzGAmJTdMfUJjoSDXkaUInwjiIz1HfqA=="
        crossorigin="anonymous"></script>
@yield('script')
@yield('script_ready')

<script type="text/javascript">
    $(function () {
        // document.addEventListener('contextmenu', function(e) {
        //     e.preventDefault();
        // });
        //
        // $(document).keydown(function(e){
        //     if(e.which === 123){
        //         return false;
        //     }
        // });
    });

    @if(session('error'))
    Swal.fire(
        'เกิดข้อผิดพลาด!',
        {{ session('error') }},
        'error'
    )
    @endif

    @if(session('success'))
    Swal.fire(
        'สำเร็จ!',
        {{ session('success') }},
        'success'
    )
    @endif

    @if($errors->any())
    @foreach($errors->all() as $error)
    Swal.fire(
        'เกิดข้อผิดพลาด!',
        '{{ session($error) }}',
        'error'
    )
    @endforeach
    @endif
</script>

</body>

</html>
<!-- end document-->
