<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Model\Category;
use App\Model\SubCategory;
use Image;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.category.category');
    }


    public function categoryList(Request $request)
    {

        $category = Category::orderBy('updated_at', 'desc');

        if ($request->keyword != '') {
            $category->where('category_name', 'LIKE', '%' . $request->keyword . '%')
                ->orWhere('category_native_name', 'LIKE', '%' . $request->keyword . '%');

        }

        $category = $category->paginate(10);


        return CategoryResource::collection($category);
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

            'name' => 'required|unique:categories,category_name',
            'image' => 'required|image64:jpeg,jpg,png,gif'
        ],
            [
                'image.image64' => 'File must be an image of jpeg,png,gif'
            ]);

        try {

            $category = new Category;

            $category->category_name = $request->name;
            $category->category_native_name = $request->native_name;
            $category->status = $request->status;

            $imageData = $request->get('image');

            if ($imageData) {

                $savedImageId = cloudinary()->upload($imageData, ['folder' => 'clothes-store/category/icon'])->getPublicId();

                $category->icon = $savedImageId;


            }

            $category->save();

            return response()->json(['status' => 'success', 'message' => 'Category Added Successfully !']);

        } catch (\Exception $e) {

            // return $e;

            return response()->json(['status' => 'error', 'message' => 'Opps Something Went Wrong!']);


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
        return new CategoryResource(Category::find($id));
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

            'name' => 'required|unique:categories,category_name,' . $id . ',id',
            'image' => 'nullable|image64:jpeg,jpg,png,gif'
        ],
            [
                'image.image64' => 'File must be an image of jpeg,png,gif'
            ]);

        try {

            $category = Category::find($id);

            $category->category_name = $request->name;
            $category->category_native_name = $request->native_name;
            $category->status = $request->status;

            $imageData = $request->get('image');

            if ($imageData) {

                if (!empty($category->icon)) {
                    cloudinary()->destroy($category->icon);
                }

                $savedImageId = cloudinary()->upload($imageData, ['folder' => 'clothes-store/category/icon'])->getPublicId();

                $category->icon = $savedImageId;


            }

            $category->save();

            return response()->json(['status' => 'success', 'message' => 'Category  Successfully Updated!']);

        } catch (\Exception $e) {

            // return $e;
            error_log( $e);
            return response()->json(['status' => 'error', 'message' => 'Opps Something Went Wrong!']);


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

        // if it have sub category then it can't be delete

        $count_subacategory = SubCategory::where('category_id', '=', $id)->count();

        if ($count_subacategory > 0) {


            return response()->json(['status' => 'error', 'message' => 'Can\'t delete the category it have sub category']);

        }

        try {
            $category = Category::find($id);

            if (!empty($category->icon)) {
                cloudinary()->destroy($category->icon);
            }

            $category->delete();

            return response()->json(['status' => 'success', 'message' => 'Category Deleted']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Something Went Wrong !']);
        }

    }
}
