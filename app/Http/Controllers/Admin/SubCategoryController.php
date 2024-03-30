<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class SubCategoryController extends Controller
{
    //construct method
    public function __construct(){
        $this->middleware('admin');
    }
     // index method for show subcategory
     public function index(Request $request){
        if($request->ajax()){
            $data = SubCategory::latest()->get();

            return DataTables::of($data)
                   ->addIndexColumn()
                   ->editColumn('category_name', function($row) {
                   return $row->category->category_name;
                   })
                   ->addColumn('image', function($row) {
                    $imagePath = asset($row->image); // Assuming 'image_path' is the column name in your database that stores the image path
                    return '<img src="'.$imagePath.'" class="img-thumbnail" width="45" height="45" />';
                   })
                   ->addColumn('action', function($row){
                    $actionBtn = '<a href="javascript:void(0)" data-id="'.$row->id.'" class="m-1 edit" data-bs-toggle="modal" data-bs-target="#editModal"><i class="fas fa-edit text-warning"></i></a>
                    <a href="'.route('subcategory.delete', [$row->id]).'" class="m-1" id="deleteSubCat"><i class="fas fa-trash text-danger"></i></a>';
                    return $actionBtn;

                   })
                   ->rawColumns(['action','image','category_name'])
                   ->make(true);
                }
                $categories = Category::latest()->get();

        return view('admin.category.subcategory.index',compact('categories'));
    }//end method

     // store method for subcategory
     public function store(Request $request) {
        // dd($request->image);
        // dd($request->all());
        $request->validate([
            'subcategory_name' => 'required|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // You might want to validate the image as well
        ]);

        // Check if image file exists in the request
        if ($request->file('image')) {
            // dd('paisi');
            $image = $request->file('image');
            $manager = new ImageManager(new Driver());
            $image_name = uniqid().'.'.$image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img->resize(115,85);
            $img->toJpeg(80)->save(base_path('public/backend/images/subcategory/'.$image_name));
            $save_url = 'backend/images/subcategory/'.$image_name;

            $data = [
                'category_id' => $request->category_id,
                'subcategory_name' => $request->subcategory_name,
                'subcategory_slug' => Str::slug($request->subcategory_name, '-'),
                'image' => $save_url,
            ];

            DB::table('sub_categories')->insert($data);

            return response()->json([
                'message' => 'Subcategory inserted successfully.'
            ]);
        } else {
            // Handle case where image file is not present in the request
            return response()->json([
                'message' => 'Image file is required.'
            ], 400);
        }
    }
    // subcategory edit
    public function edit($id){
        $data = DB::table('sub_categories')->where('id',$id)->first();
        $categories = DB::table('categories')->get();
        return view('admin.category.subcategory.edit',compact('data','categories'));

    }//end method

    // subcategory delete
    public function destroy($id){
        DB::table('sub_categories')->where('id',$id)->delete();
        return response()->json([
            'message' => 'Subcategory Deleted successfully.'
        ]);

    }

    // subcategory update method
    public function update(Request $request, $id) {
        // Validate the request data
        $request->validate([
            'subcategory_name' => 'required|max:255',
         // You might want to validate the image as well
        ]);

        // Find the subcategory by its ID
        $subcategory = DB::table('sub_categories')->where('id', $id)->first();

        if ($subcategory) {
            // If image is provided in the request, update it
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $manager = new ImageManager(new Driver());
                $image_name = uniqid().'.'.$image->getClientOriginalExtension();
                $img = $manager->read($image);
                $img->resize(115,85);
                $img->toJpeg(80)->save(base_path('public/backend/images/subcategory/'.$image_name));
                $save_url = 'backend/images/subcategory/'.$image_name;

                // Delete the old image file if it exists
                if (File::exists(public_path($subcategory->image))) {
                    File::delete(public_path($subcategory->image));
                }
            } else {
                // If image is not provided, keep the existing one
                $save_url = $request->old_image;
            }

            // Update the subcategory data
            $data = [
                'category_id' => $request->category_id,
                'subcategory_name' => $request->subcategory_name,
                'subcategory_slug' => Str::slug($request->subcategory_name, '-'),
                'image' => $save_url,
            ];

            DB::table('sub_categories')->where('id', $id)->update($data);

            return response()->json([
                'message' => 'Subcategory updated successfully.'
            ]);
        } else {
            // Handle case where subcategory with given ID is not found
            return response()->json([
                'message' => 'Subcategory not found.'
            ], 404);
        }
    }



}
