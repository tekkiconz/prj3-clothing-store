@extends('front.master.master')

@section('title')
    {{ $seo_info->title }}
@endsection

@section('meta')


    <!-- Open Graph  -->
    <meta property="og:title" content="{{ $seo_info->title }}"/>
    <meta property="og:type" content="Ecommerce Site"/>
    <meta property="og:url" content="{{ url('/') }}"/>

    <meta property="og:image" content="{{ 'https://res.cloudinary.com/ditgrfuov/image/upload/'.$seo_info->meta_image.'.jpg' }}"/>


@endsection
@section('content')

    <!-- home slider from js  -->
    <home-slider></home-slider>
    <!-- home slider from js  -->

    <!-- home category from js  -->
    <home-category></home-category>

    <!-- home category from js  -->

    <!--  end category-->

    <!--start banner-->
    <div class="container">
        <home-offers></home-offers>
    </div>
    <!--end banner-->

    <!--  start hot deal -->
    @php
        $currency = getCurrentCurrency();
    @endphp
    <hot-deal :currency="{{ $currency }}"></hot-deal>
    <!--  end   hot deal -->
@endsection

@push('script')
    <script src="{{ asset('public/js/front.js') }}"></script>
@endpush
