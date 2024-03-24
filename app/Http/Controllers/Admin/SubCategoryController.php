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
            $data = SubCategory::all();
            return DataTables::of($data)
                   ->addIndexColumn()
                   ->addColumn('action', function($row){
                    $actionBtn = '<a href="javascript:void(0)" data-id="'.$row->id.'" class="m-1 edit" data-bs-toggle="modal" data-bs-target="#editModal"><i class="fas fa-edit text-warning"></i></a>
                    <a href="'.route('category.delete', [$row->id]).'" class="m-1" id="deleteCat"><i class="fas fa-trash text-danger"></i></a>';
                    return $actionBtn;

                   })
                   ->rawColumns(['action'])
                   ->make(true);
                }
                $categories = Category::all();

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


}
