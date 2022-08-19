<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="description" content="{!! $seo_info->description !!}">
<meta name="keywords" content="{{ $seo_info->keyword }}">
<meta name="author" content="{{ $seo_info->author }}">
<meta name="sitemap_link" content="{{ $seo_info->sitemap_link }}">
<meta property="og:site_name" content="{{ $shop_info->shop_name }}"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
@yield('meta')
<link rel="shortcut icon" href="{{ 'https://res.cloudinary.com/htcompany-cloud/image/upload/'.$shop_info->favicon.'.jpg' }}"/>
<title>@yield('title',$shop_info->shop_name)</title>

<!--bootstrap css-->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
<!--line icons-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/fonts/LineIcons.min.css') }}">
<!--style css-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
<!--setting css-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/setting.css') }}">
<!--responsive css-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/responsive.css') }}">
<!--responsive css-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/custom.css') }}">
<!--google font css-->
<link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Lobster|Lobster+Two&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Open+Sans|Yesteryear&display=swap" rel="stylesheet">
