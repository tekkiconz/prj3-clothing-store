<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Setting\Messenger;
use App\Model\Setting\GoogleAnalytic;

class MessengerController extends Controller
{
    public function index()
    {
        return view('admin.setting.messenger.messenger');
    }

    public function getFbId()
    {
        return Messenger::find(1);
    }

    public function getGoogleAppId()
    {
        return GoogleAnalytic::find(1);
    }

    public function setFbId(Request $request)
    {
        $request->validate([
            'app_id' => 'required'
        ]);
        try {
            $update = Messenger::find(1);
            $update->app_id = $request->app_id;
            $update->update();
            $message = ['status' => 'success', 'message' => 'Facebook Page Id Updated Successfully!'];
        } catch (Exception $e) {
            $message = ['status' => 'error', 'message' => $e->errorInfo[2]];
        }
        return $message;
    }

    public function setFbStatus()
    {
        $result = Messenger::find(1);
        if ($result->status == 1) {
            $result->status = 0;
            $message = 'Facebook Chat  Deactivated!';
        } else {

            $result->status = 1;
            $message = 'Facebook Chat  Activated!';
        }
        $result->update();
        return response()->json(['status' => 'success', 'message' => $message]);
    }

    public function setGoogleAppId(Request $request)
    {
        $request->validate([
            'app_id' => 'required'
        ]);
        try {
            $update = GoogleAnalytic::find(1);
            $update->app_id = $request->app_id;
            $update->update();
            $message = ['status' => 'success', 'message' => 'Google Analytics ID updated!'];
        } catch (Exception $e) {
            $message = ['status' => 'error', 'message' => $e->errorInfo[2]];
        }
        return $message;
    }

    public function setGoogleStatus()
    {
        $result = GoogleAnalytic::find(1);
        if ($result->status == 1) {
            $result->status = 0;
            $message = 'Google Analytics Deactivated!';
        } else {
            $result->status = 1;
            $message = 'Google Analytics Activated!';
        }
        $result->update();
        return response()->json(['status' => 'success', 'message' => $message]);
    }


}
