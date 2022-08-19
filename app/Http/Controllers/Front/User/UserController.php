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
    public function logout()
    {
        Auth::logout();
        Session::flash('success', 'You are Logout!');
        return redirect()->to('/');
    }
}
