<?php

namespace App\Http\Controllers\Front;

use App\AllStatic;
use App\Http\Controllers\Controller;
use App\Http\Resources\Offer\CampaignResource;
use App\Http\Resources\Product\BrandResource;
use App\Http\Resources\Product\CategoryResource;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\Product\SubCategoryResource;
use App\Http\Resources\Product\SubSubCategoryResource;
use App\Model\Brand;
use App\Model\Campaign;
use App\Model\Category;
use App\Model\Product;
use App\Model\Setting\PageSetting;
use App\Model\SubCategory;
use App\Model\SubSubCategory;
use App\Model\SubCategoryBrand;
use App\Model\Subscribe;
use Illuminate\Http\Request;

class WebController extends Controller
{

    public function index()
    {
        //    return frontCategory();
        return view('front.index');
    }

    public function ShowPage($slug)
    {
        $data = PageSetting::where('title', str_replace('-', ' ', $slug))->first();
        return view('front.include.page', ['data' => $data]);
    }

    public function categoryList()
    {

        $category = Category::where('status', '=', AllStatic::$active)->orderBy('rank')->get();

        return CategoryResource::collection($category);

    }

    public function homeOffers()
    {

        $campaign = Campaign::select('id', 'title', 'image')
            ->orderBy('updated_at', 'desc')
            ->where('status', '=', 1)
            ->take(3)
            ->get();

        return CampaignResource::collection($campaign);

    }

    public function hotDeal()
    {
        $product = Product::orderBy('updated_at', 'desc')
            ->where('status', '=', AllStatic::$active)
            ->where('hot_deal', '=', AllStatic::$active)
            ->paginate(12);

        return ProductResource::collection($product);
    }

    public function searchProduct(Request $request)
    {

        $search_keyword = $request->keyword;
        $product = Product::where('status', '=', AllStatic::$active)->orderBy('updated_at', 'desc');

        if ($request->category != '') {
            $product->where('category_id', '=', $request->category);
        }
        if ($request->sub_category != '') {
            $product->where('sub_category_id', '=', $request->sub_category);
        }

        if ($request->brand != '') {
            $product->where('brand_id', '=', $request->brand);
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

    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:subscribers',
        ]);
        try {
            Subscribe::create($request->all());
            return response()->json(['status' => 'success', 'message' => 'You Subscribed Successfully, Thank You!']);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMassage()]);
        }

    }

    public function categoryProduct($id, $slug)
    {
        $category = new CategoryResource(Category::with(['sub_category' => function ($query) {
            $query->where('status', '=', AllStatic::$active);
        }])
            ->find($id));

        return view('front.category.category', ['category' => $category]);

    }

    public function viewList()
    {
        $subcategory = SubCategory::where('status', '=', AllStatic::$active)->get();

        return SubCategoryResource::collection($subcategory);

    }

    public function CategoryProductList($id)
    {

        $product = Product::where('status', '=', AllStatic::$active)
            ->where('category_id', '=', $id)
            ->paginate(8);

        return ProductResource::collection($product);

    }

    public function subCategory($id, $slug = null)
    {

        $sub_category = SubCategory::with(['category:id,category_name',
            'sub_sub_category' => function ($query) {
                $query->where('status', '=', AllStatic::$active);
            }])
            ->find($id);

        return view('front.sub-category.sub_category',
            [
                'sub_category' => new SubCategoryResource($sub_category),
            ]);

    }

    public function subCategoryProductList(Request $request, $id)
    {

        $product = Product::where('status', '=', AllStatic::$active)
            ->where('sub_category_id', '=', $id);

        if ($request->brand_id != '') {
            $product->where('brand_id', '=', $request->brand_id);
        }

        $product = $product->paginate(12);

        return ProductResource::collection($product);

    }

    // sub sub category or level three

    public function subSubCategory($id, $slug = null)
    {

        $sub_sub_category = SubSubCategory::with('sub_category')
            ->find($id);

        $brand_id = SubCategoryBrand::where('sub_sub_category_id', '=', $id)
            ->where('status', '=', AllStatic::$active)
            ->pluck('brand_id');

        $sub_sub_category_brand = Brand::select('id', 'brand_name', 'brand_native_name', 'brand_logo')
            ->orderBy('brand_name', 'asc')
            ->whereIn('id', $brand_id)->where('status', AllStatic::$active)->get();

        return view('front.sub-sub-category.sub_sub_category',
            [
                'sub_sub_category' => new SubSubCategoryResource($sub_sub_category),
                'brands' => BrandResource::collection($sub_sub_category_brand),
            ]);

    }

    public function subSubCategoryProductList(Request $request, $id)
    {

        $product = Product::where('status', '=', AllStatic::$active)
            ->where('sub_sub_category_id', '=', $id);

        if ($request->brand_id != '') {
            $product->where('brand_id', '=', $request->brand_id);
        }

        $product = $product->paginate(12);

        return ProductResource::collection($product);

    }

    public function productDetails($id, $slug)
    {
        $product = Product::with(['category:id,category_name', 'sub_category:id,sub_category_name', 'brand:id,brand_name', 'multiple_image'])->find($id);

        return view('front.product.single_product', ['product' => new ProductResource($product)]);
    }


}
