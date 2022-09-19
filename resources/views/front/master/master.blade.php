<!DOCTYPE html>
<html lang="en-us">

    <head>
    @include('front.include.header_asset')

    @stack('style')


    <!-- google analytics  -->
        <!-- coming from app/Helpers/helper -->
        @php
            $google_analytics = googleAnalytics();
        @endphp

        @if($google_analytics && $google_analytics->status == 1)
            <script async src="https://www.googletagmanager.com/gtag/js?id={{ $google_analytics->app_id }}"></script>
            <script>
                window.dataLayer = window.dataLayer || [];

                function gtag() {
                    dataLayer.push(arguments);
                }

                gtag('js', new Date());

                gtag('config', '{{ $google_analytics->app_id }}');

            </script>
    @endif
    <!-- google analytics  -->
    </head>

    <body id="app_body">
        <div id="front-wrapper">
            <header>
                <nav class="navbar navbar-expand-lg navbar-fixed-top">
                    <div class="left-menu-toggle">
                        <a id="sidebarCollapse" href="#" class="toggle-nav btn-nav nav-toggle-btn">
                            <i class="lni-menu"></i>
                        </a>
                    </div>
                    <div class="logo px-3 mr-auto">
                        <a href="{{ url('/') }}">
                            <x-cld-image public-id="{{ $shop_info->logo_header }}" loading="lazy"
                                         alt="logo"></x-cld-image>
                        </a>
                    </div>
                    <!---for mobile---->
                    <div class="md-device" style="width:50px!important;text-align:right;padding-right:0!important;">
                        <div class="user-menu">
                            <a href="#">
                                <i class="lni lni-user" style="font-size: 20px;text-align:right;"></i>
                            </a>
                            <ul class="dropdown-menu1">
                                @auth
                                    <li><a href="{{ route('user.profile') }}"><span>Profile</span></a></li>
                                    <li><a href="{{ route('user.order') }}"><span>My Orders</span></a></li>
                                    <li><a href="{{ route('user.logout') }}"><span>Logout</span></a></li>
                                @endauth
                                @guest
                                    <li><a href="{{ route('login') }}"><span>Login</span></a></li>
                                    <li><a href="{{ route('register') }}"><span>Sign up</span></a></li>
                                @endguest
                                <li><a href="{{ route('order.track') }}">Track Order</a></li>
                            </ul>
                        </div>
                    </div>
                    <!---end for mobile---->

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <search-box></search-box>

                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item active">
                                <a class="nav-link" href="{{ url('/') }}">Home</a>
                            </li>
                            <li class="nav-item active">
                                <a class="nav-link" href="{{ url('/page/About') }}">About Us</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/page/Support') }}">Support & Help</a>
                            </li>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="lni lni-user" style="font-size: 20px"></i>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <ul class="dropdown-menu1">
                                        @auth
                                            <li><a href="{{ route('user.profile') }}"><span>Profile</span></a></li>
                                            <li><a href="{{ route('user.logout') }}"><span>Logout</span></a></li>
                                        @endauth
                                        @guest
                                            <li><a href="{{ route('login') }}"><span>Login</span></a></li>
                                            <li><a href="{{ route('register') }}"><span>Sign up</span></a></li>
                                        @endguest
                                        <li><a href="{{ route('order.track') }}">Track Order</a></li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
                <!------for mobile--->
                <div class="md-device pb-3">
                    <search-box></search-box>
                </div>

                @include('front.include.cart')

            </header>
            <!--    end header-->
            <div class="wrapper">
                <!-- start Sidebar  -->

            @include('front.include.left_sidebar')

            <!-- end Sidebar  -->

                <!-- Page Content  -->
                <div id="content">
                    <!-- search result appear  -->
                    @php
                        $currency = getCurrentCurrency();
                    @endphp
                    <search-product :currency="{{ $currency  }}"></search-product>
                    <!-- search result appear  -->
                    <div class="message" style="width: 100%; height: 75px;background: #032c44; display: none;">
                        @foreach (['warning', 'success', 'error'] as $msg)
                            @if(Session::has($msg))
                                <p class="text-center"
                                   style="padding-top: 20px; font-size: 22px; color: #fff;">{{ Session::get($msg) }}</p>
                            @endif
                        @endforeach
                    </div>
                    @yield('content')

                <!--start email subscribe-->
                    <section class="subscribe clearfix mt50 text-center">
                        <user-subscribe></user-subscribe>
                    </section>
                    <!--end email subscribe-->

                    @include('front.include.footer')
                </div>
            </div>

        </div>
        <!-- End Back To Top Button -->
        <script>
            var base_url = "{{ url('/') }}" + '/';
        </script>
        <!--jquery js-->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <!--boostrap js-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
        <!--popper js-->
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <!--magnific js-->
        <script src="{{ asset('assets/js/magnific.js') }}"></script>
        <!--main js-->
        <script src="{{ asset('assets/js/main.js') }}"></script>


        @stack('script')


        <script type="text/javascript">
            $(document).ready(function ($) {
                @foreach (['warning', 'success', 'error'] as $msg)
                @if(Session::has($msg))
                $('.message').slideDown("slow").delay(4500).slideUp("slow");
                @endif
                @endforeach
            })
        </script>
        <!-- coming from app/helpers/helper  -->
        @php
            $facebook_chat = facebookChat();
        @endphp

        @if($facebook_chat && $facebook_chat->status == 1)
            <div class="fb-customerchat"
                 page_id="{{ $facebook_chat->app_id }}"
                 greeting_dialog_display="fade"
                 theme_color="{{ $shop_info->theme_color }}"
            >
            </div>
            <script>
                window.fbAsyncInit = function () {
                    FB.init({
                        appId: '1103490950182669',
                        autoLogAppEvents: true,
                        xfbml: true,
                        version: 'v11.0'
                    });
                };
                (function(d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (d.getElementById(id)) return;
                    js = d.createElement(s); js.id = id;
                    js.src = "https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js";
                    fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));
            </script>
        @endif
    </body>

</html>
<style scoped>
    /* this style are dynamic with theme  */
    .theme-background {
        background: {{ $shop_info->theme_color }}   !important;
    }

    .theme-color {
        color: {{ $shop_info->theme_color }}   !important;
    }

    .theme-hover-bg:hover {

        background: {{ $shop_info->theme_color }}   !important;

    }

    .theme-hover-color:hover {
        color: {{ $shop_info->theme_color }}   !important;
    }


    .initially_selected .parent_a {
        color: {{ $shop_info->theme_color }} ;
    }

    .active_color > a {
        color: {{ $shop_info->theme_color }} ;
    }

    .navbar {
        background: #1a94ff;
    }

    .nav-item {
        padding: 3px;
    }

    .nav-item .nav-link {
        padding-right: 1rem !important;
        padding-left: 1rem !important;
        color: #FFF;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        font-family: Roboto, sans-serif;
        border-radius: 2px;
    }

    .nav-item:hover .nav-link {
        background: #ffffff;
        color: #1a94ff;
    }

    .dropdown-menu {
        left: -108px !important;
    }

    .dropdown-menu1 li a {
        display: block;
    }

    .dropdown-menu1 li a:hover {
        color: #000000;
    }

    .dropdown-menu1 li {
        color: rgb(0, 0, 0.87);
        padding: 5px 10px;
    }

    .dropdown-menu1 li:hover {
        background: #F8F8F8;
    }

    header {
        background: #1a94ff !important;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 2222;
    }
</style>
