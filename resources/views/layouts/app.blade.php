<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-138572669-2"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-138572669-2', { cookie_flags: 'SameSite=None;Secure' });
    </script>

    <script data-ad-client="ca-pub-9153901419175702" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="description" content="@yield('pageDescription','Fresh themes for Ableton 10 with the ability to customise them yourself!')"/>
    <meta name="keywords" content="@yield('pageKeywords', 'Live Themes, Ableton, Themes, Ableton 10, Music Production, Customisation')" />
    <meta name="google-site-verification" content="ZtiXwX_AYFLsrEF26ljBRsMJdIUnl-Rsjyq4O109eRU" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta property="fb:app_id" content="503342856736879"/>
    <meta property="og:title" content="@yield('pageTitle')"/>
    <meta property="og:url" content="{{ url()->current() }}"/>
    <meta property="og:site_name" content="Live Themes"/>
    <meta property="og:image" content="@yield('pageImage', url('images/home-new.jpg'))"/>
    <meta property="og:image:width" content="@yield('pageImageWidth', 1000)"/>
    <meta property="og:image:height" content="@yield('pageImageHeight', 621)"/>
    <meta property="og:type" content="website"/>
    <meta property="og:description" content="@yield('pageDescription','Fresh themes for Ableton 10 with the ability to customise them yourself!')"/>

    <title>@yield('pageTitle', 'Download Free Themes for Ableton 10') - Live Themes</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Styles -->
    <style>[v-cloak] { display:none !important; }</style>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.14.0/css/all.css" crossorigin="anonymous">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    @yield('pagestyles')
</head>
<body class="@yield('pageClass', 'body')">

    <nav id="header" class="header" v-bind:class="classes">
        <div class="container">

            <div class="logo">

                <a href="/">
                    <svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" width="29.93" height="19.53" viewBox="0 0 29.93 19.53">
                        <path d="M20.64,10.67a3,3,0,0,1,1-2.33l6.9-6.26a2.15,2.15,0,0,1,1.31-.6,1.9,1.9,0,0,1,2,1.42,2.31,2.31,0,0,1-.18,1.35c-.24.53-.5,1-.77,1.56-.9,1.69-1.83,3.37-2.85,5a15.77,15.77,0,0,1-1.49,2.05A3.55,3.55,0,0,1,24.5,14a3.36,3.36,0,0,1-3.82-2.88C20.66,11,20.65,10.83,20.64,10.67Z" transform="translate(-2 -1.47)"/>
                        <path d="M12.41,14.7c.35.25.66.49,1,.71s.62.39.93.57a.8.8,0,0,0,.34.11.58.58,0,0,0,.62-.4,9.36,9.36,0,0,1,.46-1,3.53,3.53,0,0,1,2.58-1.8c.56-.1,1.13-.13,1.7-.19a.15.15,0,0,1,.12.07A4.36,4.36,0,0,0,22.82,15a.14.14,0,0,1,.09.08,7.13,7.13,0,0,1-.24,2.6,4.7,4.7,0,0,1-3.6,3.21,5.86,5.86,0,0,1-3.16-.13,4.57,4.57,0,0,1-2.74-2.46,7.9,7.9,0,0,1-.73-2.9C12.43,15.15,12.42,14.94,12.41,14.7Z" transform="translate(-2 -1.47)"/>
                        <polygon points="0 0.53 0 4.53 20.58 4.53 24 0.53 0 0.53"/>
                        <polygon points="9 14.53 0 14.53 0 18.53 8.37 18.53 8.39 18.42 9 14.53"/>
                        <polygon points="0 7.53 0 11.53 13.76 11.53 13.84 11.37 17 7.53 0 7.53"/>
                    </svg>
                </a>

            </div>

            <div class="main-menu">
                <ul>
                    <li>
                        <a href="/browse/all/all/featured/1">Featured</a>
                    </li>
                    <li>
                        <a href="/browse">Browse</a>
                    </li>
                    <li>
                        <a href="/charts">Charts</a>
                        <div class="new">New</div>
                    </li>
                    <li>
                        <a href="/editor">Editor</a>
                    </li>
                    <li>
                        <a href="/my-themes">My Themes</a>
                    </li>
                    <li>
                        <a href="/faq">FAQs</a>
                    </li>
                </ul>
            </div>

            <div class="user-menu">

                <i class="fal fa-user" v-on:click="sideMenu = !sideMenu"></i>

                @if (Auth::guest())
                    <span v-on:click="sideMenu = !sideMenu" class="login cp">Login / Register</span>
                @else
                    <span v-on:click="sideMenu = !sideMenu" class="name cp">{{ auth()->user()->name }}</span>
                @endif

                <span class="mobile-menu-trigger cp" v-on:click="sideMenu = !sideMenu">
                    Menu <i class="fal fa-chevron-down pl-2"></i>
                </span>

            </div>

            <transition name="side-menu">
                <div class="side-menu" v-if="sideMenu" v-cloak>

                    <i class="fal fa-times" v-on:click="sideMenu = !sideMenu"></i>

                    @if (Auth::guest())

                    <h2 class="d-block d-md-none">Menu</h2>

                    <ul class="mb-5 d-block d-md-none">
                        <li>
                            <a href="/browse/all/all/featured/1">Featured</a>
                        </li>
                        <li>
                            <a href="/browse">Browse</a>
                        </li>
                        <li>
                            <a href="/contact">Contact</a>
                        </li>
                    </ul>

                    <h2>Login</h2>

                    <form method="post" action="{{ route('login') }}">
                        @csrf

                        <label>Email</label>
                        <input type="text" name="email" class="form-control">

                        <label>Password</label>
                        <input type="password" name="password" class="form-control">

                        <div class="pb-2">
                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>

                        <input type="hidden" name="returnUrl" value="{{ url()->current() }}">

                        <div class="pt-2">
                            <button type="submit" class="btn btn-primary mr-2">Login</button>
                            <a href="/register" class="btn btn-secondary">Register</a>
                        </div>

                        <div class="pt-3">
                            <a href="/password/reset">
                                Forgot your password?
                            </a>
                        </div>

                    </form>

                    @else

                    <h2>Hi {{ auth()->user()->name }},</h2>

                    <ul>
                        <li class="d-block d-md-none">
                            <a href="/browse/all/all/featured/1">Featured</a>
                        </li>
                        <li class="d-block d-md-none">
                            <a href="/browse">Browse</a>
                        </li>
                        <li>
                            <a href="/my-themes">My Themes</a>
                        </li>
                        <li>
                            <a href="/contact">Contact</a>
                        </li>
                        <li>
                            <a href="/account">Edit Your Details</a>
                        </li>
                        <li>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                        </li>
                    </ul>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>

                    @endif

                </div>
            </transition>

        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>

    <footer class="text-center">

        <div>Made by <a href="http://monio.org" target="_blank">Monio Design</a></div>

        <span class="pd-l-sm pd-r-sm" style="opacity:0.3;">|</span>

        <div class="mb-2">Created with <span class="fal fa-heart c-primary"></span> for <a href="http://www.ableton.com" target="_blank">Ableton</a></div>

        <p class="text-small">This website is not affiliated, associated, authorized, endorsed by, or in any way officially connected with Ableton AG.<br>The official Ableton website can be found at <a href="http://www.ableton.com" target="_blank">http://www.ableton.com.</a></p>

    </footer>

    <script type="text/javascript">
        @if(!Auth::guest())
        window.user = {
            id: {{ auth()->user()->id }},
            name: "{{ auth()->user()->name }}"
        };
        @else
        window.user = null;
        @endif
    </script>

    @if(View::hasSection('pagescript'))
        @yield('pagescript')
    @else
        <script src="{{ mix('js/app.js') }}" defer></script>
    @endif

</body>
</html>
