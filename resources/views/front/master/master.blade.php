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
                <div class="logo px-3 mr-auto">
                    <a href="{{ url('/') }}">
                        <x-cld-image public-id="{{ $shop_info->logo_header }}" loading="lazy" alt="logo"></x-cld-image>
                    </a>
                </div>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="lni lni-user" style="font-size: 20px"></i>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <ul class="dropdown-menu1">
                                    @auth
                                    <li><a href="{{ route('user.logout') }}"><span>Logout</span></a></li>
                                    @endauth
                                    @guest
                                    <li><a href="{{ route('login') }}"><span>Login</span></a></li>
                                    <li><a href="{{ route('register') }}"><span>Sign up</span></a></li>
                                    @endguest
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!--    end header-->
        <div class="wrapper">

            <!-- Page Content  -->
            <div id="content">
                <div class="message" style="width: 100%; height: 75px;background: #032c44; display: none;">
                    @foreach (['warning', 'success', 'error'] as $msg)
                    @if(Session::has($msg))
                    <p class="text-center" style="padding-top: 20px; font-size: 22px; color: #fff;">{{ Session::get($msg) }}</p>
                    @endif
                    @endforeach
                </div>
                @yield('content')


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
        $(document).ready(function($) {
            @foreach(['warning', 'success', 'error'] as $msg)
            @if(Session::has($msg))
            $('.message').slideDown("slow").delay(4500).slideUp("slow");
            @endif
            @endforeach
        })
    </script>
</body>

</html>
<style scoped>
    /* this style are dynamic with theme  */
    .theme-background {
        background: {
                {
                $shop_info->theme_color
            }
        }

         !important;
    }

    .theme-color {
        color: {
                {
                $shop_info->theme_color
            }
        }

         !important;
    }

    .theme-hover-bg:hover {

        background: {
                {
                $shop_info->theme_color
            }
        }

         !important;

    }

    .theme-hover-color:hover {
        color: {
                {
                $shop_info->theme_color
            }
        }

         !important;
    }


    .initially_selected .parent_a {
        color: {
                {
                $shop_info->theme_color
            }
        }

        ;
    }

    .active_color>a {
        color: {
                {
                $shop_info->theme_color
            }
        }

        ;
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