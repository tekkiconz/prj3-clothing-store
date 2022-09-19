<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Category;
use App\Model\SubCategory;
use App\Model\SubSubCategory;
use App\Model\SubCategoryBrand;
use App\Model\Brand;
use App\Model\Product;
use App\Model\ProductImage;
use App\Model\ProductKeyword;
use App\Model\Order\OrderDetails;
use App\AllStatic;
use App\Http\Resources\Product\ProductResource;
use DB, Image, Auth, PDF;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getAllCategory($edit_time = '')
    {
        $category = Category::select('id', 'category_name', 'category_native_name')
            ->orderBy('category_name', 'desc');
        // during edit time will pass a value and will get all category active in_active
        if ($edit_time = '') {
            $category->where('status', '=', AllStatic::$active);
        }
        return $category = $category->get();
    }

    public function getSubCategoryByCategory($id)
    {
        $sub_category = SubCategory::select('id', 'sub_category_name', 'sub_category_native_name')
            ->where('category_id', '=', $id)
            ->orderBy('sub_category_name', 'ASC')
            ->get();
        return $sub_category;
    }

    public function getSubSubCategoryBySubCategory($id)
    {
        $sub_sub_category = SubSubCategory::select('id', 'sub_sub_category_name', 'sub_sub_category_native_name')
            ->where('sub_category_id', '=', $id)
            ->orderBy('sub_sub_category_name', 'ASC')
            ->get();
        return $sub_sub_category;
    }


    public function brandBySubcategory($id)
    {

        $brand_id = SubCategoryBrand::where('sub_sub_category_id', '=', $id)
            ->where('status', '=', AllStatic::$active)
            ->pluck('brand_id');

        $sub_category_brand = Brand::select('id', 'brand_name', 'brand_native_name')
            ->orderBy('brand_name', 'asc')
            ->whereIn('id', $brand_id)
            ->get();

        return $sub_category_brand;
    }

    public function index()
    {

        $category = $this->getAllCategory();
        return view('admin.product.product', [
            'category' => $category
        ]);
    }

    public function productList(Request $request)
    {
        $search_keyword = $request->keyword;
        $product = Product::with([
            'category:id,category_name,category_native_name',
            'sub_category:id,sub_category_name,sub_category_native_name',
            'sub_sub_category:id,sub_sub_category_name,sub_sub_category_native_name',
            'brand:id,brand_name,brand_native_name',
            'multiple_image:id,product_id,image_name'
        ])
            ->orderBy('updated_at', 'desc');

        if ($request->category != 'undefined') {
            $product->where('category_id', '=', $request->category);
        }
        if ($request->sub_category != 'undefined') {
            $product->where('sub_category_id', '=', $request->sub_category);
        }

        if ($request->sub_sub_category != 'undefined') {
            $product->where('sub_sub_category_id', '=', $request->sub_sub_category);
        }

        if ($request->brand != 'undefined') {
            $product->where('brand_id', '=', $request->brand);
        }

        if ($request->range != '') {
            $date = $request->range;
            $data = explode(",", $date);
            $start = date_convert($data[0]);
            $end = date_convert($data[1]);
            $product->whereBetween('updated_at', [$start, $end]);
        }

        if ($search_keyword != '') {
            // this three field  or combination doing a and combination with all other combination in upper
            $product->where(function ($query) use ($search_keyword) {
                $query->where('product_name', 'LIKE', '%' . $search_keyword . '%')
                    ->orWhere('product_native_name', 'LIKE', '%' . $search_keyword . '%')
                    ->orWhere('product_keyword', 'LIKE', '%' . $search_keyword . '%');
            });
        }
        $product = $product->paginate(12);

        return ProductResource::collection($product);

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
            'product_name' => 'required',
            'category' => 'required',
            'sub_category' => 'required',
            'quantity' => 'required|integer',
            'buying_price' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
            'selling_price' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
            'image' => 'required|image64:jpeg,png,gif,jpg,webp,bmp',
            'attachments.*' => 'nullable|mimes:jpeg,png,gif,jpg,webp,bmp',
        ],
            [
                'image.required' => 'Feature Image Is Required',
                'image.image64' => 'Feature Image must to be a type of jpeg,png,gif,jpg,webp,bmp',
                'image.attachments.mimes' => 'Feature Image must have to be a  type of jpeg,png,gif,jpg,webp,bmp',

            ]);

        try {

            DB::beginTransaction();

            // product adding code will added here

            $product = new Product;

            $product->product_name = $request->product_name;
            $product->product_native_name = $request->product_native_name;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->sub_sub_category_id = $request->sub_sub_category;
            $product->brand_id = $request->brand;
            $product->quantity_unit = $request->quantity_unit;
            $product->stock_quantity = $request->quantity;
            $product->current_quantity = $request->quantity;
            $product->buying_price = $request->buying_price;
            $product->selling_price = $request->selling_price;
            $product->product_description = $request->description;
            $product->created_by = Auth::guard('admin')->user()->id;

            // if product have tag then make it string and save it to product keyword
            if ($request->product_tag) {

                $keywords = implode(', ', $request->product_tag);
                $product->product_keyword = $keywords;

            }

            $imageData = $request->get('image');

            if ($imageData) {

                $savedImageId = cloudinary()->upload($imageData, ['folder' => 'clothes-store/product/feature'])->getPublicId();

                $product->product_image = $savedImageId;

            }

            $product->save();

            // product tag
            if ($request->product_tag) {

                foreach ($request->product_tag as $value) {
                    $product_keyword = new ProductKeyword;
                    $product_keyword->keyword_name = $value;
                    $product_keyword->product_id = $product->id;
                    $product_keyword->save();
                }

            }

            if ($request->file('attachments')) {
                foreach ($request->file('attachments') as $key => $file) {
                    $imageId = cloudinary()->upload($file->getRealPath(), ['folder' => 'clothes-store/product/image'])->getPublicId();

                    $product_image = new ProductImage;
                    $product_image->image_name = $imageId;
                    $product_image->product_id = $product->id;
                    $product_image->save();
                }


            }

            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'Product Added !']);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json(['status' => 'error', 'message' => 'oops! something went wrong']);

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
        $product = new ProductResource(Product::with('category:id,category_name,category_native_name',
            'sub_category:id,sub_category_name,sub_category_native_name',
            'sub_sub_category:id,sub_sub_category_name,sub_sub_category_native_name',
            'brand:id,brand_name,brand_native_name',
            'multiple_image:id,product_id,image_name',
            'productKeyword:id,product_id,keyword_name')->find($id));

        return response()->json([
            'product' => $product,
            'categories' => $this->getAllCategory('yes'),
            'sub_categories' => $this->getSubCategoryByCategory($product->category->id),
            'sub_sub_categories' => $this->getSubSubCategoryBySubCategory($product->sub_category_id),
            'brands' => $this->brandBySubcategory($product->sub_sub_category_id),
        ]);
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
            'product_name' => 'required',
            'category' => 'required',
            'sub_category' => 'required',
            'quantity' => 'required|integer',
            'buying_price' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
            'selling_price' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
            'image' => 'nullable|image64:jpeg,png,gif,jpg,webp,bmp',
            'attachments.*' => 'nullable|mimes:jpeg,png,gif,jpg,webp,bmp',
        ],
            [
                'image.required' => 'Feature Image Is Required',
                'image.image64' => 'Feature Image must to be a type of jpeg,png,gif,jpg,webp,bmp',
                'image.attachments.mimes' => 'Feature Image must have to be a  type of jpeg,png,gif,jpg,webp,bmp',

            ]);

        try {

            DB::beginTransaction();

            // product adding code will be added here

            $product = Product::find($id);

            $product->product_name = $request->product_name;
            $product->product_native_name = $request->product_native_name;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->sub_sub_category_id = $request->sub_sub_category;

            if ($request->brand != 0) {
                $product->brand_id = $request->brand;
            }

            if ($request->quantity >= $product->current_quantity) {

                $increment = $request->quantity - $product->current_quantity;

                $product->stock_quantity = $product->stock_quantity + $increment;

            } else {

                $decrement = $product->current_quantity - $request->quantity;

                $product->stock_quantity = $product->stock_quantity - $decrement;

            }

            $product->quantity_unit = $request->quantity_unit;
            $product->current_quantity = $request->quantity;
            $product->buying_price = $request->buying_price;
            $product->selling_price = $request->selling_price;
            $product->product_description = $request->description;
            $product->updated_by = Auth::guard('admin')->user()->id;

            // if product have tag then make it string and save it to product keyword
            if ($request->product_tag) {

                $keywords = implode(', ', $request->product_tag);
                $product->product_keyword = $keywords;

            }

            $imageData = $request->get('image');

            if ($imageData) {

                if (!empty($product->product_image)) {
                    cloudinary()->destroy($product->product_image);
                }

                $savedImageId = cloudinary()->upload($imageData, ['folder' => 'clothes-store/product/feature'])->getPublicId();


                $product->product_image = $savedImageId;

            }

            $product->update();


            // product tag
            if ($request->product_tag) {

                // delete old tag

                ProductKeyword::where('product_id', '=', $id)->delete();

                foreach ($request->product_tag as $value) {

                    $product_keyword = new ProductKeyword;
                    $product_keyword->keyword_name = $value;
                    $product_keyword->product_id = $product->id;
                    $product_keyword->save();
                }

            }

            if ($request->file('attachments')) {
                foreach ($request->file('attachments') as $key => $file) {
                    $imageId = cloudinary()->upload($file->getRealPath(), ['folder' => 'clothes-store/product/image'])->getPublicId();

                    $product_image = new ProductImage;
                    $product_image->image_name = $imageId;
                    $product_image->product_id = $product->id;
                    $product_image->save();
                }

            }
            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'Product updated !']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['status' => 'error', 'message' => 'oops! something went wrong']);
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

            // check order details

            $count = OrderDetails::where('product_id', '=', $id)->count();
            if ($count > 0) {
                return response()->json(['status' => 'error', 'message' => 'This Product Has Sale']);
            }

            DB::beginTransaction();

            ProductKeyword::where('product_id', '=', $id)->delete();

            $product_image = ProductImage::where('product_id', '=', $id)->get();

            if (count($product_image) > 0) {

                foreach ($product_image as $value) {

                    $p_image = ProductImage::find($value->id);

                    if (!empty($p_image->image_name)) {
                        cloudinary()->destroy($p_image->image_name);
                    }
                    $p_image->delete();

                }
            }

            $product = Product::find($id);

            if (!empty($product->product_image)) {
                cloudinary()->destroy($product->product_image);
            }

            $product->delete();

            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'Product Deleted']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['status' => 'error', 'message' => 'Something Went Wrong !']);

        }
    }


    public function deleteImage($id)
    {

        try {

            $p_image = ProductImage::find($id);

            if (!empty($p_image->image_name)) {
                cloudinary()->destroy($p_image->image_name);
            }

            $p_image->delete();

            return response()->json(['status' => 'success', 'message' => 'image Deleted !']);

        } catch (\Exception $e) {

            return response()->json(['status' => 'error', 'message' => 'something went wrong !']);

        }

    }

    public function deactiveProduct($id)
    {

        try {

            $product = Product::find($id);

            $product->status = $product->status == AllStatic::$active ? AllStatic::$inactive : AllStatic::$active;


            $message = $product->status == AllStatic::$active ? 'Product Deactivated !' : 'Product Activated';

            $product->update();

            return response()->json(['status' => 'success', 'message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'something went wrong !']);
        }
    }


    public function hotDealStatus($id)
    {

        try {

            $product = Product::find($id);

            $product->hot_deal = $product->hot_deal == AllStatic::$active ? AllStatic::$inactive : AllStatic::$active;


            $message = $product->hot_deal == AllStatic::$active ? 'Product Added to Hot Deal !' : 'Product Remove From Hot Deal';

            $product->update();

            return response()->json(['status' => 'success', 'message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'something went wrong !']);
        }
    }

    public function getForDiscount($id)
    {
        return new ProductResource(Product::find($id));
    }

    public function setDiscount(Request $request)
    {
        $request->validate([
            'discount' => 'required|numeric|gt:0'
        ]);

        $status = $request->discount_status ? 1 : 0;
        Product::where('id', '=', $request->id)
            ->update([
                'discount' => (float) $request->discount,
                'discount_amount' => (float) $request->discount_amount,
                'discount_type' => $request->discount_type,
                'discount_status' => $status
            ]);
        return response()->json(['status' => 'success', 'message' => 'Discount Added Successful!']);
    }
}
