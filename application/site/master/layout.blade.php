<!DOCTYPE html>
<html lang="en" data-ng-app="website">
<!-- Google Tag Manager -->
<script type="text/javascript" src="{{asset('/js/jquery.floating-social-share.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/js/jquery-latest.min.js')}}"></script>

<!-- End Google Tag Manager -->
<head>


    <meta charset="utf-8">
    <title>Liberty Churches - {{ucwords(substr(strrchr(url()->current(),"/"),1))}}</title>
    <link rel="SHORTCUT ICON" href="{{asset('favicon.ico')}}"
          type="image/vnd.microsoft.icon"/>
    <link rel="canonical" href="index.html"/>
    <meta name="author" content="Samuel Rocha">
    <meta property="og:title" content="Liberty Churches"/>
    <meta property="og:type" content="website"/>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="{{ucwords(substr(strrchr(url()->current(),"/"),1))}}">
    <meta name="keywords" content="liberty, churches, God, Shrewsbury, bible, faith">
    <meta property="og:image" content="{{asset('www/content/img/hear-clean.jpg')}}">
    <meta property="og:image:secure_url" content="{{asset('www/content/img/hear-clean.jpg')}}">

    <link rel="stylesheet" href="{{asset('/css/assets.min-_build=1582886060.css')}}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/v4-shims.css">

    <link rel="stylesheet" href="{{asset('css/colorbox.css')}}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="{{asset('js/jquery.colorbox-min.js')}}"></script>
    <script>
        $(document).ready(function(){
            //Examples of how to assign the Colorbox event to elements
            $(".group1").colorbox({rel:'group1'});
            $(".iframe").colorbox({iframe:true, width:"80%", height:"80%"});
        });
    </script>
    <style>@import url("https://fonts.googleapis.com/css?family=Lato:100,100italic,300,300italic,regular,italic,700,700italic,900,900italic%7CLora:regular,italic,700,700italic%7CMontserrat:100,100italic,200,200italic,300,300italic,regular,italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic%7COpen+Sans:300,300italic,regular,italic,600,600italic,700,700italic,800,800italic%7COswald:300,regular,700&subset=latin,latin-ext,cyrillic,vietnamese,devanagari,cyrillic-ext,greek-ext,greek");
        @import url("https://fonts.googleapis.com/css?family=Pacifico:regular%7CRaleway:100,100italic,200,200italic,300,300italic,regular,italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic%7CRoboto:100,100italic,300,300italic,regular,italic,500,500italic,700,700italic,900,900italic%7CRubik:300,300italic,regular,italic,500,500italic,700,700italic,900,900italic&subset=latin,latin-ext,cyrillic,vietnamese,devanagari,cyrillic-ext,greek-ext,greek");
    </style>
    <link rel="stylesheet" href="{{asset('css/styles-_build=1582893276.css')}}" id="moto-website-style"/>
    <script type='text/javascript' src='https://platform-api.sharethis.com/js/sharethis.js#property=5e72a08fca14fe0012fd2d33&product=sticky-share-buttons&cms=website' async='async'></script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-QZ1EKPC2LM"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-QZ1EKPC2LM');
    </script>
</head>
<body class="moto-background moto-website_live">

<!-- Google Tag Manager (noscript) -->
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TJN9CW8"
            height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->
<div class="page">
    <header id="section-header" class="header moto-section" data-widget="section" data-container="section">
        <div class="moto-widget moto-widget-block moto-bg-color5_5 moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-auto moto-spacing-left-auto" data-widget="block" data-spacing="aaaa" style="background-color:">
            <div class="container-fluid">
                <div class="row">
                    <div class="moto-cell col-sm-12" data-container="container">
                        <div class="moto-widget moto-widget-container moto-container_header_591adcc71"
                             data-widget="container" data-container="container"
                             data-css-name="moto-container_header_591adcc71" data-moto-sticky="{ }">
                            <div class="moto-widget moto-widget-row row-fixed moto-justify-content_center moto-spacing-top-small moto-spacing-right-auto moto-spacing-bottom-small moto-spacing-left-auto"
                                data-grid-type="sm" data-widget="row" data-spacing="sasa">
                                <div class="container-fluid">
                                    <div class="row" data-container="container">
                                        <div class="moto-widget moto-widget-row__column moto-cell col-sm-2"
                                             data-widget="row.column" data-bgcolor-class=""
                                             data-container="container">
                                            <div data-widget-id="wid__image__5e591f1c66800"
                                                 class="moto-widget moto-widget-image moto-preset-default moto-align-left_mobile-h moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-auto moto-spacing-left-auto  "
                                                 data-widget="image">
                                                <a href="{{ route('site.home') }}" data-action="page"
                                                   class="moto-widget-image-link moto-link">
                                                    <img data-src="{{asset('img/logo.png')}}" class="moto-widget-image-picture lazyload" data-id="174" title="" alt="">
                                                </a>
                                            </div>
                                        </div>
                                        <div class="moto-widget moto-widget-row__column moto-cell col-sm-10"
                                             data-widget="row.column" data-bgcolor-class=""
                                             data-container="container">
                                            <div data-widget-id="wid__menu__5e591f1c872d7"
                                                 class="moto-widget moto-widget-menu moto-preset-default moto-align-right moto-align-center_mobile-h moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-auto moto-spacing-left-auto"
                                                 data-preset="default" data-widget="menu">
                                                <a class="moto-widget-menu-toggle-btn"><i
                                                        class="moto-widget-menu-toggle-btn-icon fa fa-bars"></i></a>
                                                <ul class="moto-widget-menu-list moto-widget-menu-list_horizontal">
                                                    <li class="moto-widget-menu-item moto-widget-menu-item-has-submenu">
                                                        <a href="{{ route('site.about-us') }}" data-action="page" class="moto-widget-menu-link moto-widget-menu-link-level-1 {{ Route::current()->getName() === 'site.about-us' ? 'moto-widget-menu-link-active' : '' }}  moto-link">About us
                                                            <span class="fa moto-widget-menu-link-arrow"></span>
                                                        </a>
                                                        <ul class="moto-widget-menu-sublist">
                                                            <li class="moto-widget-menu-item">
                                                                <a href="{{ route('site.team') }}"
                                                                   class="moto-widget-menu-link moto-widget-menu-link-level-2 moto-link">Our Team</a>
                                                            </li>
                                                            <li class="moto-widget-menu-item moto-widget-menu-item-has-submenu">
                                                                <a href="{{ route('site.contact') }}" data-action="page"
                                                                   class="moto-widget-menu-link moto-widget-menu-link-level-2 moto-widget-menu-link-submenu moto-link">Contact<span
                                                                        class="fa moto-widget-menu-link-arrow"></span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <li class="moto-widget-menu-item">
                                                        <a href="{{ route('site.covid-19') }}" data-action="page" class="moto-widget-menu-link moto-widget-menu-link-level-1 {{ Route::current()->getName() === 'site.covid-19' ? 'moto-widget-menu-link-active' : '' }} moto-link">Covid-19</a>
                                                    </li>
                                                    <li class="moto-widget-menu-item">
                                                        <a href="{{ route('site.messages') }}" data-action="page"
                                                           class="moto-widget-menu-link moto-widget-menu-link-level-1 {{ Route::current()->getName() === 'site.messages' ? 'moto-widget-menu-link-active' : '' }} moto-link">Messages</a>
                                                    </li>
                                                    <li class="moto-widget-menu-item">
                                                        <a  href="#" data-action="page" class="moto-widget-menu-link moto-widget-menu-link-level-1 moto-link">Get Connected</a>
                                                        <ul class="moto-widget-menu-sublist">
                                                            <li class="moto-widget-menu-item moto-widget-menu-item-has-submenu">
                                                                <a href="{{ route('site.obv') }}" data-action="page" class="moto-widget-menu-link moto-widget-menu-link-level-2 moto-widget-menu-link-submenu moto-link">One Big Vision<span
                                                                        class="fa moto-widget-menu-link-arrow"></span>
                                                                </a>
                                                            </li>
                                                            <li class="moto-widget-menu-item moto-widget-menu-item-has-submenu">
                                                                <a href="{{ route('site.ministry') }}" data-action="page" class="moto-widget-menu-link moto-widget-menu-link-level-2 moto-widget-menu-link-submenu moto-link">Ministries<span
                                                                        class="fa moto-widget-menu-link-arrow"></span>
                                                                </a>
                                                            </li>
                                                               <li class="moto-widget-menu-item moto-widget-menu-item-has-submenu">
                                                                   <a href="{{ route('site.calendar') }}" data-action="page"
                                                                      class="moto-widget-menu-link moto-widget-menu-link-level-2 moto-widget-menu-link-submenu moto-link">Calendar<span
                                                                           class="fa moto-widget-menu-link-arrow"></span>
                                                                   </a>
                                                               </li>
                                                            <li class="moto-widget-menu-item moto-widget-menu-item-has-submenu">
                                                                <a href="{{ route('site.connect') }}" data-action="page"
                                                                   class="moto-widget-menu-link moto-widget-menu-link-level-2 moto-widget-menu-link-submenu moto-link">Connect Groups<span
                                                                        class="fa moto-widget-menu-link-arrow"></span>
                                                                </a>
                                                            </li>
                                                            <li class="moto-widget-menu-item">
                                                                <a href="#" data-action="page"
                                                                   class="moto-widget-menu-link moto-widget-menu-link-level-2 moto-link">Forms</a>
                                                                <ul class="moto-widget-menu-sublist">
                                                                    <li class="moto-widget-menu-item">
                                                                        <a href="{{ route('site.prayer') }}"
                                                                           data-action="blog.index"
                                                                           class="moto-widget-menu-link moto-widget-menu-link-level-3 moto-link">Prayer</a>
                                                                    </li>
                                                                    <li class="moto-widget-menu-item">
                                                                        <a href="{{ route('site.wedding') }}"
                                                                           data-action="blog.index"
                                                                           class="moto-widget-menu-link moto-widget-menu-link-level-3 moto-link">Wedding</a>
                                                                    </li>
                                                                    <li class="moto-widget-menu-item">
                                                                        <a href="{{ route('site.new-here') }}"
                                                                           data-action="blog.index"
                                                                           class="moto-widget-menu-link moto-widget-menu-link-level-3 moto-link">New Here</a>
                                                                    </li>
                                                                    <li class="moto-widget-menu-item">
                                                                        <a href="{{ route('site.membership') }}"
                                                                           data-action="blog.index"
                                                                           class="moto-widget-menu-link moto-widget-menu-link-level-3 moto-link">Membership</a>
                                                                    </li>
                                                                    <li class="moto-widget-menu-item">
                                                                        <a href="{{ route('site.baptism') }}"
                                                                           data-action="blog.index"
                                                                           class="moto-widget-menu-link moto-widget-menu-link-level-3 moto-link">Water Baptism</a>
                                                                    </li>
                                                                    <li class="moto-widget-menu-item">
                                                                        <a href="{{ route('site.baby-dedication') }}"
                                                                           data-action="blog.index"
                                                                           class="moto-widget-menu-link moto-widget-menu-link-level-3 moto-link">Baby Dediction</a>
                                                                    </li>
                                                                </ul>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <li class="moto-widget-menu-item">
                                                        <a href="{{ route('site.online-church') }}" data-action="page"
                                                           class="moto-widget-menu-link moto-widget-menu-link-level-1 {{ Route::current()->getName() === 'site.online-church' ? 'moto-widget-menu-link-active' : '' }} moto-link">Online Church</a>
                                                    </li>
                                                    <li class="moto-widget-menu-item">
                                                        <a href="{{ route('site.education') }}" data-action="page"
                                                           class="moto-widget-menu-link moto-widget-menu-link-level-1 {{ Route::current()->getName() === 'site.education' ? 'moto-widget-menu-link-active' : '' }} moto-link">Education</a>
                                                    </li>
                                                    <li class="moto-widget-menu-item">
                                                        <a href="{{ route('site.giving') }}" data-action="page"
                                                           class="moto-widget-menu-link moto-widget-menu-link-level-1  {{ Route::current()->getName() === 'site.giving' ? 'moto-widget-menu-link-active' : '' }} moto-link">Giving</a>
                                                    </li>

                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    @yield('content')
</div>

@extends('site.master.footer')

<div data-moto-back-to-top-button class="moto-back-to-top-button">
    <a ng-click="toTop($event)" class="moto-back-to-top-button-link">
        <span class="moto-back-to-top-button-icon fa"></span>
    </a>
</div>


<script src="{{asset('js/website.assets.min-_build=1582885837.js')}}" type="text/javascript"
        data-cfasync="false"></script>
<script type="text/javascript" data-cfasync="false">
    var websiteConfig = websiteConfig || {};
    websiteConfig.address = 'https://template63456.motopreview.com/';
    websiteConfig.addressHash = 'edeb72c35283ee73801fc2889241a9f6';
    websiteConfig.apiUrl = '/api.php';
    websiteConfig.preferredLocale = 'en_US';
    websiteConfig.preferredLanguage = websiteConfig.preferredLocale.substring(0, 2);
    websiteConfig.back_to_top_button = {"topOffset": 300, "animationTime": 500, "type": "theme"};
    websiteConfig.popup_preferences = {"loading_error_message": "The content could not be loaded."};
    websiteConfig.lazy_loading = {"enabled": true};
    websiteConfig.cookie_notification = {
        "enable": false,
        "content": "<p class=\"moto-text_normal\" style=\"text-align: justify;\">This website uses cookies to ensure you get the best experience on our website.<\/p>",
        "content_hash": "94404561c632a6e603b0c7479e510b68"
    };
    angular.module('website.plugins', []);
</script>

<script src="{{asset('js/website.min-_build=1582885830.js')}}" type="text/javascript"
        data-cfasync="false"></script>
<script type="text/javascript">$.fn.motoGoogleMap.setApiKey('AIzaSyCPbz3W389x_owB2TlrqPuMEYCTFVuRvMY');</script>


</body>
</html>

