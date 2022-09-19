<?php

namespace App\Http\Controllers\Offers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Product;
use App\Model\Campaign;
use App\AllStatic;
use Image;
use DB;
use App\Http\Resources\Offer\CampaignResource;
use App\Http\Resources\Product\ProductResource;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.offers.campaign.campaign');
    }

    public function productList(Request $request)
    {

        if ($request->keyword != '') {

            $search_keyword = $request->keyword;

            $product = Product::where(function ($query) use ($search_keyword) {
                $query->where('product_name', 'LIKE', '%' . $search_keyword . '%')
                    ->orWhere('product_native_name', 'LIKE', '%' . $search_keyword . '%')
                    ->orWhere('product_keyword', 'LIKE', '%' . $search_keyword . '%');
            })
                ->where('status', AllStatic::$active)
                ->where('discount_status', '=', AllStatic::$inactive)
                ->orderBy('product_name', 'ASC')
                ->get();


            return ProductResource::collection($product);

        } else {

            return response()->json(['status' => 'not-found']);

        }


    }

    public function offerList(Request $request)
    {

        $campaign = Campaign::orderBy('updated_at', 'desc');

        if ($request->keyword != '') {

            $campaign->where('title', 'LIKE', '%' . $request->keyword . '%');
        }
        $campaign = $campaign->paginate(10);

        return CampaignResource::collection($campaign);


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
        $request->validate([
            'campaign_title' => 'required',
            'banner' => 'required|image64:jpeg,png,gif,jpg,webp,bmp',
            'meta_image' => 'required|image64:jpeg,png,gif,jpg,webp,bmp',
            'status' => 'required',

        ]);

        try {

            DB::beginTransaction();

            $camp = new Campaign;
            $camp->title = $request->campaign_title;
            $camp->status = $request->status;

            $imageData = $request->get('banner');
            $metaImage = $request->get('meta_image');

            if ($imageData) {
                $savedImageId = cloudinary()->upload($imageData, ['folder' => 'clothes-store/campaign/banner'])->getPublicId();

                $camp->image = $savedImageId;

            }

            if ($metaImage) {
                $metaId = cloudinary()->upload($metaImage, ['folder' => 'clothes-store/campaign/meta'])->getPublicId();

                $camp->meta_image = $metaId;

            }

            $request->start_date = date('Y-M-d');
            $request->end_date = date('Y-M-d');


            $camp->save();

            if (count($request->product) > 0) {
                foreach ($request->product as $value) {

                    $product = Product::find($value['id']);
                    $product->discount_status = $request->status;
                    $product->discount = $value['discount'];
                    $product->discount_amount = $value['discount_amount'];
                    $product->discount_type = $value['discount_type'];
                    $product->campaign_id = $camp->id;
                    $product->update();
                }

            }

            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'Campaign Added!']);
        } catch (Exceptoin $e) {
            DB::rollBack();

            return response()->json(['status' => 'error', 'message' => 'Something Went Wrong']);
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
        $campaign = Campaign::with('product')->find($id);

        return new CampaignResource($campaign);
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
        $request->validate([
            'campaign_title' => 'required',
            'banner' => 'nullable|image64:jpeg,png,gif,jpg,webp,bmp',
            'meta_image' => 'nullable|image64:jpeg,png,gif,jpg,webp,bmp',
            'status' => 'required',

        ]);

        try {

            DB::beginTransaction();

            $camp = Campaign::find($id);
            $camp->title = $request->campaign_title;
            $camp->status = $request->status;

            $imageData = $request->get('banner');
            $metaImage = $request->get('meta_image');


            if ($imageData) {
                if (!empty($camp->image)) {
                    cloudinary()->destroy($camp->image);
                }

                $savedImageId = cloudinary()->upload($imageData, ['folder' => 'clothes-store/campaign/banner'])->getPublicId();

                $camp->image = $savedImageId;

            }

            if ($metaImage) {
                if (!empty($camp->meta_image)) {
                    cloudinary()->destroy($camp->meta_image);
                }

                $metaId = cloudinary()->upload($metaImage, ['folder' => 'clothes-store/campaign/meta'])->getPublicId();

                $camp->meta_image = $metaId;

            }

            $request->start_date = date('Y-M-d');
            $request->end_date = date('Y-M-d');
            $camp->update();

            Product::where('campaign_id', '=', $id)
                ->update([
                    'discount' => 0,
                    'discount' => 0,
                    'discount_amount' => 0,
                    'discount_type' => 1,
                    'discount_status' => 0,
                    'campaign_id' => 0,
                ]);

            if (count($request->product) > 0) {
                foreach ($request->product as $value) {

                    $product = Product::find($value['id']);
                    $product->discount_status = $request->status;
                    $product->discount = $value['discount'];
                    $product->discount_amount = $value['discount_amount'];
                    $product->discount_type = $value['discount_type'];
                    $product->campaign_id = $camp->id;
                    $product->update();
                }

            }

            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'Campaign Added!']);


        } catch (Exceptoin $e) {
            DB::rollBack();

            return response()->json(['status' => 'error', 'message' => 'Something Went Wrong']);
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
            DB::beginTransaction();
            $camp = Campaign::find($id);
            if (!empty($camp->image)) {
                cloudinary()->destroy($camp->image);
            }

            if (!empty($camp->meta_image)) {
                cloudinary()->destroy($camp->meta_image);
            }

            $camp->delete();

            Product::where('campaign_id', '=', $id)
                ->update([
                    'discount' => 0,
                    'discount' => 0,
                    'discount_amount' => 0,
                    'discount_type' => 1,
                    'discount_status' => 0,
                    'campaign_id' => 0,
                ]);

            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'Campaign Deleted  !']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['status' => 'error', 'message' => 'Something Went Wrong !']);
        }
    }

    public function offerStatus($id)
    {
        try {

            DB::beginTransaction();

            $camp = Campaign::find($id);

            $camp->status = $camp->status == AllStatic::$active ? AllStatic::$inactive : AllStatic::$active;

            $message = $camp->status == AllStatic::$active ? 'Campaign Activated!' : 'Campaign Deactivated!';

            $camp->update();

            Product::where('campaign_id', '=', $camp->id)->update(['discount_status' => $camp->status]);

            DB::commit();

            return response()->json(['status' => 'success', 'message' => $message]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['status' => 'error', 'message' => 'Something went wrong!']);

        }
    }

    public function getOffer($id, $offer_name)
    {
        $campaign = Campaign::find($id);
        // return $campaign;
        return view('front.offers.OfferProduct', ['campaign' => $campaign]);
    }

    public function allOffer()
    {
        return view('front.offers.offers');
    }

    public function getOfferProduct($id)
    {
        $product = Product::where('campaign_id', $id)
            ->where('discount_status', '=', 1)
            ->where('status', '=', 1)
            ->get();
        return ProductResource::collection($product);
    }
}
