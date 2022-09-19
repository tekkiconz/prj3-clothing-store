<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Cart;
use App\Model\Setting\ShippingArea;
use App\Model\Setting\ShippingCost;
use App\Model\Setting\PaymentSetting;
use App\Model\Product;
use App\Model\Order\Order;
use App\Model\Order\OrderDetails;
use App\AllStatic;
use App\User;
use DB;
use Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function shippingAmount()
    {

        $shipping = ShippingCost::find(1);
        return $shipping;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function CartItem()
    {

        $cart_items = Cart::content();
        $cart_total = Cart::subtotal();
        $cart_count = Cart::count();

        return response()->json([
            'cart_items' => $cart_items,
            'cart_total' => (float) $cart_total,
            'cart_count' => $cart_count
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        try {

            $id = $request->id;
            $product_name = $request->product_name;
            $qty = $request->qty;
            $price = $request->price;
            $current_qty = $request->current_qty;
            $image = $request->product_image;
            $qty_unit = $request->qty_unit;
            $discount = $request->discount;

            if ($qty_unit != '') {
                $product_name = $product_name . '(' . $qty_unit . ')';
            }

            // checking available in stock
            if ($qty > $current_qty) {
                return response()->json(['status' => 'error', 'message' => 'Product out of Stock']);

            }
            $cart = Cart::content()->where('id', $id)->first();

            // checking if cart have the product alredy
            if ($cart) {

                if ($cart->qty + $qty > $current_qty) {
                    return response()->json(['status' => 'error', 'message' => 'Product out of Stock']);
                }

            }

            Cart::add(['id' => $id, 'name' => $product_name, 'qty' => $qty, 'price' => $price, 'weight' => 0, 'options' => ['image' => $image, 'stock' => $current_qty, 'discount' => (float) $discount], 'discount' => 0]);


            return response()->json(['status' => 'success', 'message' => 'Product Added To Cart']);
        } catch (\Exception $e) {

            return $e;
        }
    }

    public function checkOutPage()
    {
        $payment_method = PaymentSetting::select('id', 'provider')->where('status', '=', 1)->get();
        return view('front.checkout.checkout', [
            'payment_method' => $payment_method
        ]);

    }


    public function checkoutStore(Request $request)
    {

        $request->validate(
            [
                'name' => 'required',
                'phone' => 'required',
                'delivery_area' => 'required',
                'address' => 'required',
                'payment_method' => 'required',
                'cart_total' => 'required|numeric|gt:0',
                'card_no' => 'required_if:payment_method,3',
                'cvvNumber' => 'required_if:payment_method,3|max:4',
                'expire_month' => 'nullable|required_if:payment_method,3|date_format:m|size:2',
                'expire_year' => 'nullable|required_if:payment_method,3|date_format:Y|size:4'
            ],
            [
                'payment_method.required' => 'please chose a payment method',
                'card_no.required_if' => 'Please Enter Card No',
                'cvvNumber.required_if' => 'Please Enter CVC',
                'expire_month.required_if' => 'Please Enter Month',
                'expire_year.required_if' => 'Please Enter Expired Year',
                'expire_month.date_format' => 'Invalid format try ex:04',
                'expire_year.date_format' => 'Invalid format try ex:2030',
                'cart_total.*' => 'Your Cart is Empty',
            ]);

        try {

            $order_id = generate_order_no();

            DB::beginTransaction();

            $total_discount = 0;
            $total_buying_amount = 0;

            foreach (Cart::content() as $value) {

                $product = Product::find($value->id);

                $details = new OrderDetails;

                $details->order_id = $order_id;
                $details->category_id = $product->category_id;
                $details->sub_category_id = $product->sub_category_id;
                $details->sub_sub_category_id = $product->sub_sub_category_id;
                $details->brand_id = $product->brand_id ?: 0;
                $details->product_id = $value->id;
                $details->user_id = Auth::user()->id;
                $details->quantity = $value->qty;
                $details->selling_price = $value->price;
                $details->buying_price = $product->buying_price;
                $details->total_buying_price = $product->buying_price * $value->qty;
                $details->total_selling_price = $value->price * $value->qty;
                $details->unit_discount = $value->options->discount;
                $details->total_discount = $value->options->discount * $value->qty;

                $details->save();

                // total discount
                $total_discount += $value->qty * $value->options->discount;
                // total buying amount
                $total_buying_amount += $value->qty * $product->buying_price;
                // minus from stock
                $product->current_quantity = $product->current_quantity - $value->qty;
                $product->total_sold = $product->total_sold + $value->qty;

                $product->update();

            }

            // Saving invoice information
            $order = new Order;

            $order->id = $order_id;
            $order->user_id = Auth::user()->id;
            $order->shipping_area_id = $request->delivery_area;
            $order->customer_name = $request->name;
            $order->phone = $request->phone;
            $order->email = $request->email;
            $order->address = $request->address;
            $order->shipping_amount = $request->delivery_cost;
            $order->total_item = Cart::count();
            $order->total_amount = $request->cart_total;
            $order->discount = $total_discount;
            $order->total_buying_amount = $total_buying_amount;
            $order->payment_status = 0;
            $order->payment_method = 0;
            $order->order_date = date('Y-m-d');
            $order->status = 0;
            $order->save();

            // if information checked to update for next time in profile

            if ($request->profile_update == 1) {
                $user = User::find(Auth::user()->id);
                $user->name = $request->name;
                $user->location_id = $request->delivery_area;
                $user->phone = $request->phone;
                $user->address = $request->address;
                $user->update();

            }

            DB::commit();

            Cart::destroy();
            if ($request->payment_method == AllStatic::$cash) {
                return redirect()->route('order.completed', [
                    'flug' => 1,
                    'status' => 'success',
                    'message' => 'Order no #' . $order_id . '. taken as  cash on delivery
                    if u want to pay now
                    click on My Order button ',
                ]);
            } elseif ($request->payment_method == AllStatic::$paypal) {
                return redirect()->route('paypal', $order_id);
            } elseif ($request->payment_method == AllStatic::$stripe) {
                return redirect()->route('addmoney.stripe',
                    [
                        'order_id' => $order_id,
                        'cvc' => $request->cvvNumber,
                        'card_no' => $request->card_no,
                        'expire_month' => $request->expire_month,
                        'expire_year' => $request->expire_year,
                        'state' => 'order'
                    ]);
            } elseif ($request->payment_method == AllStatic::$ssl) {
                return redirect()->route('payment.ssl', $order_id);
            } else {

                // default order will save as cash on delivery

                return redirect()->route('order.completed', [
                    'flug' => 1,
                    'status' => 'success',
                    'message' => 'Order no #' . $order_id . '. taken as  cash on delivery
                    if u want to pay now
                    click on My Order button',
                ]);
            }

        } catch (\Exception $e) {

            DB::rollback();

            return $e->getMessage();
        }


    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, $status)
    {
        $cart = Cart::get($id);

        if ($status == 'decrement') {
            $qty = $cart->qty - 1;

            Cart::update($id, $qty);

            return response()->json(['status' => 'success', 'message' => 'Item Decreased']);
        } else {

            if ($cart->qty + 1 > $cart->options->stock) {
                return response()->json(['status' => 'error', 'message' => 'out of stock']);
            } else {

                Cart::update($id, $cart->qty + 1);
                return response()->json(['status' => 'success', 'message' => 'Item Increased']);

            }
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            Cart::remove($id);

            return response()->json(['status' => 'success', 'message' => 'item removed']);
        } catch (\Exception $e) {
            return $e;
        }
    }

}
