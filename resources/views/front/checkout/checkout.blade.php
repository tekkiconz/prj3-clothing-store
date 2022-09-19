@extends('front.master.master')

@section('title')
    {{ $shop_info->shop_name }} | Checkout
@endsection

@section('meta')
    <!-- Twitter Card  -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:site" content="@Hieutran3003200">
    <meta name="twitter:creator" content="@Hieutran3003200">

    <!-- Open Graph  -->
    <meta property="og:title" content=" {{ $shop_info->shop_name }} | Checkout"/>
    <meta property="og:type" content="Ecommerce Site"/>
    <meta property="og:url" content="{{ route('checkout.get') }}"/>
    <meta property="og:image"
          content="{{ 'https://res.cloudinary.com/ditgrfuov/image/upload/'.$seo_info->meta_image.'.jpg' }}"/>
    <meta property="og:description" content="{!! $seo_info->description !!}"/>

@endsection

@section('content')

    <section class="checkout">
        <div class="bg-overlay pt50 pb50">
            <div class="container">
                <form method="POST" action="{{ route('checkout.store') }}" id="#">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6 col-sm-12">
                            <div class="bg-white bg-shadow ">
                                <div class="heading  clearfix p10">
                                    <h4 class=" color-black">Shipping Information</h4>
                                </div>

                                <small
                                    class="heading heading-solid center-block heading-width-100 border-light"></small>


                                <div class="clearfix p20">

                                    <div class="form-group">
                                        <label for="name">Name <span class="text-danger">*
                                            @error('name')
                                            {{ $message }}
                                            @enderror
                                        </span>
                                        </label>
                                        <input value="{{ Auth::user()->name }}" class="form-control" name="name"
                                               placeholder="Name" type="text" id="name">
                                        <p class="ptsan-regular"></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Phone <span class="text-danger">*
                                    @error('phone')
                                                {{ $message }}
                                                @enderror
                                    </span></label>
                                        <input value="{{ Auth::user()->phone }}" class="form-control" name="phone"
                                               placeholder="Phone" type="text" id="name">
                                        <p class="ptsan-regular"></p>
                                    </div>

                                    <div class="form-group select-box">
                                        <label>Delivery Area
                                            <span class="text-danger">
                                                @error('delivery_area')
                                                {{ $message }}
                                                @enderror
                                            </span>
                                        </label>
                                        <select class="form-control" name="delivery_area">
                                            <option value="">Select Area</option>
                                            @foreach(getLocationData() as $area)
                                                <option value="{{ $area->id }}" {{ Auth::user()->location_id == $area->id ? 'selected' : '' }}>{{ $area->city }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="address">Address
                                            <span class="text-danger">*
                                                @error('address')
                                                {{ $message }}
                                                @enderror
                                            </span>
                                        </label>
                                        <textarea rows="3" class="form-control" name="address" placeholder="Address"
                                                  id="address">{{ Auth::user()->address }}</textarea>
                                        <p class="ptsan-regular"></p>
                                    </div>

                                    <div class="form-group">
                                        <input id="info-update" type="checkbox"
                                               value="1"
                                               name="profile_update"
                                               class="input-radio"
                                        >
                                        <label for="info-update">
                                            Update my profile with this information
                                        </label>
                                    </div>

                                </div>
                            </div>
                            <!--   end user info-->
                            <div class="form bg-white bg-shadow mt30">
                                <div class="heading  clearfix p10">
                                    <h4 class="color-black">Payment Method</h4>
                                </div>
                                <small
                                    class="heading heading-solid center-block heading-width-100 border-light"></small>
                                <div class="p20">
                                    @error('payment_method')
                                    <span class="text-danger">
                                    {{ $message }}
                                    </span>
                                    @enderror
                                    <div class="payment-form">

                                        @foreach($payment_method as $value)
                                            <div class="payment-method">
                                                <input id="payment-method-{{ str_replace(' ','-',$value->provider) }}"
                                                       class="input-radio" name="payment_method"
                                                       value="{{ $value->id }}" type="radio" checked>
                                                <label
                                                    for="payment-method-{{ str_replace(' ','-',$value->provider) }}">{{ ucwords(str_replace('-',' ',$value->provider)) }}</label>
                                                @if($value->id == 2)
                                                    <img src="{{ url('assets/image/payment/paypal-payment-icon.png') }}"
                                                         alt="Paypal Image">
                                                @endif
                                                @if($value->id == 3)
                                                    <img src="{{ url('assets/image/payment/stripe-payment-icon.png') }}"
                                                         alt="Stripe Image">
                                                @endif
                                                @if($value->id == 4)
                                                    <img src="{{ url('assets/image/payment/ssl.jpg') }}"
                                                         class="img-fluid" alt="SSL  Commerz">
                                                @endif

                                            <!-- card no option for stripe payment  -->
                                                @if($value->id == 3)
                                                    <div class="pay-box payment_method_stripe row">
                                                        <div class="col-md-12">
                                                            <input type="text" name="card_no"
                                                                   class="form-control stripe_input"
                                                                   placeholder="Card No">
                                                            @error('card_no')
                                                            <span class="text-danger">

                                                            {{ $message }}

                                                            </span>
                                                            @enderror
                                                            <input type="text" name="cvvNumber"
                                                                   class="form-control stripe_input"
                                                                   placeholder="CVC">
                                                            @error('cvvNumber')
                                                            <span class="text-danger">

                                                            {{ $message }}

                                                            </span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-6 col-6">
                                                            <input type="text" name="expire_month"
                                                                   class="form-control stripe_input"
                                                                   placeholder="Expired Month EX:04">
                                                            @error('expire_month')
                                                            <span class="text-danger">
                                                            {{ $message }}
                                                            </span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-6 col-6">
                                                            <input type="text" name="expire_year"
                                                                   class="form-control stripe_input"
                                                                   placeholder="Expired Year EX:2030">
                                                            @error('expire_year')
                                                            <span class="text-danger">
                                                            {{ $message }}
                                                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                        * 15% vat will applicable with all orders.

                                        <button class="button button-md theme-background color-white mb20 w-100"
                                                type="submit">Place Order
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--left-->
                        <div class="col-lg-6 col-sm-12">
                            @error('cart_total')
                            <h5 class="text-danger text-center">
                                {{ $message }}
                            </h5>
                            @enderror
                            <h5>
                                @php
                                    $currency = getCurrentCurrency();
                                @endphp
                                <checkout-cart :currency="{{ $currency }}"></checkout-cart>
                            </h5>
                        </div>
                        <!--left-->
                    </div>
                </form>
            </div>
        </div>
    </section>

@endsection

@push('script')
    <script src="{{ asset('public/js/front.js') }}"></script>
@endpush
