@extends('front.master.master')

@section('title')
    {{ $data->title }} | {{ $shop_info->shop_name }}
@endsection

@section('meta')
    <!-- Twitter Card  -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:site" content="@Hieutran3003200">
    <meta name="twitter:creator" content="@Hieutran3003200">

    <!-- Open Graph  -->
    <meta property="og:title" content="{{ $data->title }}"/>
    <meta property="og:type" content="Ecommerce Site"/>
    <meta property="og:url" content="{{ url('/') }}"/>
    <!-- <meta property="og:price:amount" content="$15.00" /> -->
@endsection
@section('content')

    <div class="container mt-4">

        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="text-center">
                            <div class="row category">
                                <div class="col-md-12 title_box">
                                    <div class="title text-center">
                                        <h4>{!! $data->title !!}</h4>
                                    </div>
                                    <div class="title_border"></div>
                                </div>
                            </div>
                        </div>
                        <div style="text-align: center;">
                            {!! $data->description !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
@push('script')
    <script src="{{ asset('public/js/front.js') }}"></script>
@endpush
