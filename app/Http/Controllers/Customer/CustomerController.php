<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Model\Order\Order;
use App\Model\Order\OrderDetails;

class CustomerController extends Controller
{
    public function index()
    {
        return view('admin.customer.customers');
    }

    public function getCustomer(Request $request)
    {

        $customer = User::orderBy('updated_at', 'desc');

        if ($request->keyword != '') {
            $customer->where('name', 'LIKE', '%' . $request->keyword . '%')
                ->orWhere('email', 'LIKE', '%' . $request->keyword . '%')
                ->orWhere('phone', 'LIKE', '%' . $request->keyword . '%');
        }


        $customer = $customer->paginate(10);

        return $customer;
    }

    public function destroy($id)
    {
        return User::find($id)->delete();
    }

    public function customerOrder($id)
    {
        return Order::with(['user', 'provider:id,provider'])->where('user_id', $id)->get();
    }

    public function customerOrderDetails($id)
    {
        return OrderDetails::where('order_id', $id)->get();
    }

    public function changeStatus($id)
    {
        $customer = User::find($id);

        if ($customer->status == 0) {
            $customer->status = 1;
            $message = 'User Activated!';
        } else {
            $customer->status = 0;
            $message = 'User Deactivated!';
        }

        $customer->update();

        return response()->json(['status' => 'success', 'message' => $message]);
    }
}
