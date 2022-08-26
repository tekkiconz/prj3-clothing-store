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

@endsection

@push('script')
    <script src="{{ asset('public/js/front.js') }}"></script>
@endpush
