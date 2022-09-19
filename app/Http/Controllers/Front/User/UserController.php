<?php

namespace App\Http\Controllers\Front\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth, Session, Hash, Image, PDF;
use App\User;
use App\Model\Order\Order;
use App\Model\Order\OrderDetails;
use App\Model\Setting\ShopSetting;
use \Mail;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('front.user.dashboard');
    }

    public function dashboardData()
    {

        $order = Order::where('user_id', '=', Auth::user()->id)->count();
        $total_discount = Order::where('user_id', '=', Auth::user()->id)->sum('discount');
        $product = OrderDetails::where('user_id', '=', Auth::user()->id)->sum('quantity');

        return response()->json([
            'total_order' => $order,
            'total_product' => $product,
            'total_discount' => $total_discount,
        ]);

    }

    public function profileUpdate()
    {
        return view('front.user.update_profile');
    }

    public function order()
    {
        return view('front.user.orders');
    }


    public function getUserOrder()
    {
        $orders = Order::with('shipping_area:id,city')
            ->orderBy('id', 'desc')
            ->where('user_id', Auth::id())
            ->paginate(10);
        return $orders;
    }

    public function getUserOrderDetails($id)
    {

        $orderdetails = OrderDetails::with('product:id,product_name,product_image,quantity_unit')->where('order_id', '=', $id)->get();
        return $orderdetails;
    }

    public function OrderDetailsPdf($id)
    {
        $orderdetails = OrderDetails::with('product:id,product_name,product_image')->where('order_id', '=', $id)->get();
        $order = Order::with(['user:id,name,email', 'order_details.product'])->find($id);
        // return view('front.user.pdf.orderdetailspdf',['orderdetails' => $orderdetails, 'order' => $order]);

        $pdf = PDF::loadView('front.user.pdf.orderdetailspdf', ['orderdetails' => $orderdetails, 'order' => $order]);

        $pdf->setPaper('A4', 'landscape');
        return $pdf->download("orderdetails-Pdf-" . $id . ".pdf");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    public function authenticateUser()

    {
        $user = Auth::user();

        return ['user' => $user, 'location' => getLocationData()];
    }

    public function storeUpdateProfile(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $request->id,
            'phone' => 'required|unique:users,phone,' . $request->id,
            'location_id' => 'required',
            'address' => 'required',
            'image' => 'nullable|image64:jpeg,png,gif,jpg,webp,bmp',
        ], [
            'image.image64' => 'image must have to be jpeg,png,gif,jpg,webp,bmp'
        ]);

        try {

            $user = User::find(Auth::user()->id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->location_id = $request->location_id;
            $user->address = $request->address;

            $imageData = $request->get('image');

            if ($imageData) {

                if (!empty($user->avatar)) {
                    cloudinary()->destroy($user->avatar);
                }

                $savedImageId = cloudinary()->upload($imageData, ['folder' => 'clothes-store/avatar'])->getPublicId();

                $user->avatar = $savedImageId;

            }

            $user->update();

            return response()->json(['status' => 'success', 'message' => 'Profile Updated']);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);

        }
    }

    public function passwordChange()
    {
        return view('front.user.changePassword');
    }

    public function storeNewPassword(Request $request)
    {
        $request->validate([
            'oldpassword' => 'required',
            'password' => 'required|confirmed|min:6'
        ]);

        $hasPassword = Auth::user()->password;

        if (Hash::check($request->oldpassword, $hasPassword)) {
            $user = User::find(Auth::id());
            $user->password = Hash::make($request->password);
            $user->save();
            Auth::logout();
            Session::flash('success', 'Password Changed Successfully!');
            return redirect()->route('login');
        } else {
            Session::flash('error', 'Current Password is Invalid!');
            return redirect()->back();
        }
    }

    public function sendEmailLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $token = \Hash::make(substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'), 0, 6));
        $result = User::where('email', $request->email)->first();
        $result->remember_token = $token;
        $result->update();

        $shop_info = ShopSetting::orderBy('id', 'desc')->first();
        $subject = 'Confim Email For Reset User Password';
        $to = $request->email;
        $email = $shop_info->email;
        $name = $shop_info->shop_name;
        Mail::send('auth.passwords.reset_email_temp', ['token' => $token],
            function ($message) use ($to, $email, $name, $subject) {
                $message->from($email, $name);
                $message->to($to)->subject($subject);
            });
        \Session::flash('status', 'A fresh verification link has been sent to your email address.');
        return redirect()->back();
    }

    public function viewUserResetPage(Request $request)
    {
        $result = User::where('remember_token', $request->token)->first();
        if ($result->count() > 0) {
            return view('auth.passwords.confirm', ['token' => $request->token]);
        } else {
            Session::flash('error', 'Please, try again!!');
            return redirect()->back();
        }
    }

    public function storeResetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|min:6'
        ]);
        $user = User::where('remember_token', $request->identity)->first();
        $user->password = Hash::make($request->password);
        $user->update();
        Auth::logout();
        Session::flash('success', 'Password is Changed Successfully!');
        return redirect()->route('login');
    }

    public function logout()
    {
        Auth::logout();
        Session::flash('success', 'You are Logout!');
        return redirect()->to('/');
    }
}
