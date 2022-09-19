@extends('front.master.master')

@section('title')
    {{ $shop_info->shop_name }} | Checkout
@endsection

@section('meta')

    <!-- Open Graph  -->
    <meta property="og:title" content=" {{ $shop_info->shop_name }} | Checkout"/>
    <meta property="og:type" content="Ecommerce Site"/>
    <meta property="og:url" content="{{ route('checkout.get') }}"/>
    <meta property="og:image" content="{{ 'https://res.cloudinary.com/ditgrfuov/image/upload/'.$seo_info->meta_image.'.jpg' }}"/>
    <meta property="og:description" content="{!! $seo_info->description !!}"/>
@endsection

@section('content')

    <div class="container">
        <div class="row order_box">
            <div class="col-md-6 ml-auto mr-auto text-center">
                <div class="box bg-shadow">
                    <div class="order-heading">

                        @if($status == 'success')
                            <h2>Your Order Completed</h2>
                            <img src="{{ asset('images/static/success_green.png') }}" class="img-fluid">
                        @else
                            <h2>Your Payment Failed</h2>
                            <img src="{{ asset('images/static/success_red.png') }}" class="img-fluid">
                        @endif
                    </div>
                    <div class="order-body @if($status == 'error') error_status @endif">
                        <p>
                            {{ $message }}
                        </p>
                    </div>

                    <div class="order-footer ">
                        <a title="Go to Home Page" href="{{ url('/') }}" class="btn theme-background color-white">Back
                            to Home</a>
                        <a title="Visit Your Order" href="{{ route('user.order') }}"
                           class="btn theme-background color-white">My Order</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{ asset('public/js/front.js') }}"></script>
@endpush
