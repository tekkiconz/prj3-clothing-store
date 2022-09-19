@extends('front.master.master')
@section('meta')
    <!-- Twitter Card  -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:site" content="@Hieutran3003200">
    <meta name="twitter:creator" content="@Hieutran3003200">

    <!-- Open Graph  -->
    <meta property="og:title" content="{{ $seo_info->title }}"/>
    <meta property="og:type" content="Ecommerce Site"/>
    <meta property="og:url" content="{{ url('/') }}"/>
    <meta property="og:image" content="{{ 'https://res.cloudinary.com/ditgrfuov/image/upload/'.$seo_info->meta_image.'.jpg' }}"/>
    <meta property="og:description" content="{{ $seo_info->description }}"/>
@endsection
@section('content')

    <!--start banner-->
    <div class="container">
        <all-offers></all-offers>
    </div>
    <!--end banner-->
@endsection

@push('script')
    <script src="{{ asset('public/js/front.js') }}"></script>
@endpush
