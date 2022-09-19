<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Invoice-{{ $order->id }}</title>
        <style>
            .clearfix:after {
                content: "";
                display: table;
                clear: both
            }

            a {
                color: #5d6975;
                text-decoration: underline
            }

            body {
                position: relative;
                width: 21cm;
                min-height: 20cm;
                margin: 0 auto;
                color: #001028;
                background: #fff;
                font-family: Arial, sans-serif;
                font-size: 0.9em;
                font-family: Arial
            }

            header {
                padding: 10px 0;
                margin-bottom: 30px;
            }

            #logo {
                text-align: center;
                margin-bottom: 10px;
                background: #345B2C;
            }

            #logo img { /*width:90px*/
            }

            h1 {
                border-top: 1px solid #5d6975;
                border-bottom: 1px solid #5d6975;
                color: #5d6975;
                font-size: 1.6em;
                line-height: 1.4em;
                font-weight: 400;
                text-align: center;
                margin: 0 0 20px 0;
            }

            #project {
                float: left
            }

            #project span {
                color: #5d6975;
                text-align: right;
                width: 52px;
                margin-right: 10px;
                display: inline-block;
                font-size: .8em
            }

            #company {
                float: right;
            }

            #company div, #project div {
                white-space: nowrap
            }

            table {
                width: 100%;
                border-collapse: collapse;
                border-spacing: 0;
                margin-bottom: 20px
            }

            table tr:nth-child(2n-1) td {
                background: #f5f5f5
            }

            table td, table th {
                text-align: right;
            }

            table th {
                padding: 5px 20px;
                color: #5d6975;
                border-bottom: 1px solid #c1ced9;
                white-space: nowrap;
                font-weight: 400
            }

            table .desc, table .service {
                text-align: left
            }

            table td {
                padding: 8px;
                text-align: right
            }

            table td.desc, table td.service {
                vertical-align: top
            }

            table td.qty, table td.total, table td.unit {
                font-size: 1em;
                text-align: right;
            }

            table td.grand {
                border-top: 1px solid #5d6975;
            }

            #notices .notice {
                color: #5d6975;
                font-size: 1.2em
            }

            footer {
                color: #5d6975;
                width: 100%;
                height: 30px;
                position: absolute;
                bottom: 0;
                border-top: 1px solid #c1ced9;
                padding: 8px 0;
                text-align: center
            }

            .discount-price {
                text-decoration: line-through;
                color: red;
            }
        </style>
        <script src="{{ asset('assets/js/jquery-3.2.1.min.js')}}"></script>
    </head>
    <body>
        @php
            $currency = getCurrentCurrency();
        @endphp
        <header class="clearfix">
            <div id="logo">
                <x-cld-image public-id="{{ $shop_info->logo_header }}" loading="lazy"></x-cld-image>
            </div>
            <h1>INVOICE #{{ $order->id }}</h1>
            <div id="company" class="clearfix">
                <div>{{ $shop_info->shop_name }}</div>
                <div>{{ $shop_info->address }}</div>
                <div>{{ $shop_info->phone }}</div>
                <div>{{ $shop_info->email }}</div>
            </div>
            <div id="project">
                <div><span>ORDER NO.</span> {{ $order->id }}</div>
                <div><span>CLIENT</span> {{ $order->customer_name }}</div>
                <div><span>ADDRESS</span> {{ $order->address }}</div>
                <div><span>EMAIL</span>{{ $order->user->email }}</div>
                <div><span>PHONE</span> {{ $order->phone }}</div>
                <div><span>DATE</span> {{ $order->order_date }}</div>
            </div>
        </header>
        <main>
            <table>
                <thead>
                    <tr>
                        <th class="service">ITEM</th>
                        <th>QTY</th>
                        <th>UNIT PRICE</th>
                        <th>TOTAL PRICE</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->order_details as $detail)
                        <tr>
                            <td class="service">{{ $detail->product->product_name }}</td>
                            <td class="qty">{{ $detail->quantity }} pcs</td>
                            <td class="unit">{{ $detail->selling_price }}@if($detail->unit_discount > 0)<span
                                    class="discount-price">{{ $detail->selling_price + $detail->unit_discount }}</span>@endif
                            </td>
                            <td class="total">{{ $detail->quantity * $detail->selling_price }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="3">SUBTOTAL</td>
                        <td class="total">{{ $order->total_amount }}</td>
                    </tr>
                    <tr>
                        <td colspan="3">SHIPPING</td>
                        <td class="total">{{ $order->shipping_amount }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="grand total">GRAND TOTAL</td>
                        <td class="grand total">{{ $currency->code }} {{ $order->total_amount + $order->shipping_amount }}</td>
                    </tr>
                </tbody>
            </table>
            <!--
            <div id="notices">
              <div>NOTICE:</div>
              <div class="notice">A finance charge of 1.5% will be made on unpaid balances after 30 days.</div>
            </div>
          -->
        </main>
        <footer>
            * 15% vat will applicable with all orders.
        </footer>

        <script>
            $(document).ready(function () {
                window.print();
                window.close();
            })
        </script>
    </body>
</html>
