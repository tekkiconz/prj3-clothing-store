<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use URL;
use Session;
use Redirect;
use Input;
use App\User;

// use Stripe\Error\Card;
use Stripe;
use App\Model\Order\Order;
use App\Model\Order\OrderDetails;
use App\Model\Setting\PaymentSetting;
use App\Model\Currency;
use App\AllStatic;

class StripeController extends Controller
{

    public function postPaymentStripe(Request $request, $order_id)
    {

        $cred = PaymentSetting::where(['provider' => 'stripe', 'status' => 1])->first();
        $currency = Currency::where('currency_status', 1)->first();
        $order_data = Order::find($order_id);

        $stripe = Stripe::setApiKey("$cred->client_secret");

        try {
            $token = $stripe->tokens()->create([
                'card' => [
                    'number' => $request->card_no,
                    'exp_month' => $request->expire_month,
                    'exp_year' => $request->expire_year,
                    'cvc' => $request->cvc,
                ],
            ]);

            if (!isset($token['id'])) {
                return redirect()->route('checkout.get');
            }
            $charge = $stripe->charges()->create([
                'card' => $token['id'],
                'currency' => $currency->code,
                'amount' => (float) $order_data->total_amount,
                'description' => 'wallet',
            ]);

            if ($charge['status'] == 'succeeded') {
                $order = Order::find($order_id);

                $order->payment_status = AllStatic::$paid;
                $order->payment_method = AllStatic::$stripe;
                $order->payment_date = date('Y-m-d');
                $order->update();


                return redirect()->route('order.completed', [

                    'flug' => 1,
                    'status' => 'success',
                    'message' => 'Payment success for  #' . $order_id . '.To see your order
			click on My Order button',
                ]);

            } else {

                return redirect()->route('order.completed', [
                    'flug' => 1,
                    'status' => 'error',
                    'message' => 'Payment failed for  #' . $order_id . '.It\'s currently on cash on delivery,
            if u want to pay click on My Order button',
                ]);

            }
        } catch (Exception | \Cartalyst\Stripe\Exception\CardErrorException | \Cartalyst\Stripe\Exception\MissingParameterException $e) {

            return redirect()->route('order.completed', [

                'flug' => 1,
                'status' => 'error',
                'message' => 'Payment failed for order #' . $order_id . ' due to ' . $e->getMessage() . ' Order taken as
            cash on delivery, if u wants to pay click on My Order button',
            ]);
        }
    }
}
