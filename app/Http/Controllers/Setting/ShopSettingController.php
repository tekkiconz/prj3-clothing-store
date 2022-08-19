<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Setting\ShopSetting;
use Image;

class ShopSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.setting.shop.shop');
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

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'shop_name' => 'required',
                'address' => 'required',
                'footer_text' => 'required',
                'phone' => 'required',
                'email' => 'required',
                'theme_color' => 'required',
                'header_logo' => 'nullable|image64:jpeg,png,gif,jpg,webp,bmp',
                'favicon' => 'nullable|image64:jpeg,png,gif,jpg,webp,bmp',
                'footer_logo' => 'nullable|image64:jpeg,png,gif,jpg,webp,bmp',
            ]
        );

        try {

            $shop = ShopSetting::find(1);
            $shop->shop_name = $request->shop_name;
            $shop->shop_short_name = $request->shop_short_name;
            $shop->address = $request->address;
            $shop->footer_text = $request->footer_text;
            $shop->phone = $request->phone;
            $shop->email = $request->email;
            $shop->facebook = $request->facebook_link;
            $shop->twitter = $request->twitter_link;
            $shop->youtube = $request->youtube_link;
            $shop->theme_color = $request->theme_color;
            $header_logo = $request->get('header_logo');
            $footer_logo = $request->get('footer_logo');
            $favicon = $request->get('favicon');
            if ($header_logo) {
                if (!empty($shop->logo_header)) {
                    cloudinary()->destroy($shop->logo_header);
                }

                $headerLogoId = cloudinary()->upload($header_logo, ['folder' => 'clothes-store/logo'])->getPublicId();

                $shop->logo_header = $headerLogoId;
            }

            if ($footer_logo) {
                if (!empty($shop->logo_footer)) {
                    cloudinary()->destroy($shop->logo_footer);
                }

                $footerLogoId = cloudinary()->upload($footer_logo, ['folder' => 'clothes-store/logo'])->getPublicId();

                $shop->logo_footer = $footerLogoId;
            }

            if ($favicon) {
                if (!empty($shop->favicon)) {
                    cloudinary()->destroy($shop->favicon);
                }

                $faviconId = cloudinary()->upload($favicon, ['folder' => 'clothes-store/logo'])->getPublicId();

                $shop->favicon = $faviconId;
            }

            $shop->update();

            return response()->json(['status' => 'success', 'message' => 'Shop Setting Updated']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Something Went Wrong !']);
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
        $shop_setting = ShopSetting::find($id);

        return $shop_setting;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getAddress()
    {
        return ShopSetting::find(1);
    }
}
