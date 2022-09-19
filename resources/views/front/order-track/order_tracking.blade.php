@extends('front.master.master')

@section('title')
    Track Your Order
@endsection

@section('meta')
    <!-- Twitter Card  -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ $seo_info->title }}">
    <meta name="twitter:site" content="@Hieutran3003200">
    <meta name="twitter:creator" content="@Hieutran3003200">
    <meta name="twitter:description" content="{!! $seo_info->description !!}">
    <meta name="twitter:image" content="{{ 'https://res.cloudinary.com/ditgrfuov/image/upload/'.$seo_info->meta_image.'.jpg' }}">

    <!-- Open Graph  -->
    <meta property="og:title" content="{{ $seo_info->title }}"/>
    <meta property="og:type" content="Ecommerce Site"/>
    <meta property="og:url" content="{{ url('/') }}"/>
    <meta property="og:image" content="{{ 'https://res.cloudinary.com/ditgrfuov/image/upload/'.$seo_info->meta_image.'.jpg' }}"/>
    <meta property="og:description" content="{!! $seo_info->description !!}"/>

@endsection
@section('content')

    @php
        $currency = getCurrentCurrency();
    @endphp

    <order-tracking :currency="{{ $currency }}"></order-tracking>



@endsection

@push('script')
    <script src="{{ asset('public/js/front.js') }}"></script>
@endpush
