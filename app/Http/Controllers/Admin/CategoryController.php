<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
   //construct method
    public function __construct(){
        $this->middleware('admin');
    }

    // index method for show category
    public function index(Request $request){
        if($request->ajax()){
            $data = Category::all();
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

        return view('admin.category.index');
    }//end method

    // store method for Category
    public function store(Request $request){
        $validated = $request->validate([
            'category_name' => 'required|unique:categories|max:40',
        ], [
            'category_name.unique' => "Category name already exists.",
            'category_name.required' => "Category name is required.",
            'category_name.max' => "Category name must not exceed 40 characters.",
        ]);

        $data = [
            'category_name' => $request->category_name,
            'category_slug' => Str::slug($request->category_name, '-'),
        ];

        DB::table('categories')->insert($data);

        return response()->json([
            'message' => 'Category inserted successfully.'
        ]);
    }//end method

    // edit method for Category
    public function edit($id){
        $data = DB::table('categories')->where('id',$id)->first();
        return view('admin.category.edit',compact('data'));

    }//end method

     // store method for Category
     public function update(Request $request){
    //  dd($request->all());
        $request->validate([
            'category_name' => 'required|unique:categories|max:40',
        ]);

        $data = [
            'category_name' => $request->category_name,
            'category_slug' => Str::slug($request->category_name, '-'),
        ];

        DB::table('categories')->where('id', $request->id)->update($data);

        return response()->json([
            'message' => 'Category Updated Successfully.'
        ]);
    }//end method

    // destroy method for Category
    public function destroy($id) {
        DB::table('categories')->where('id', $id)->delete();
            return response()->json([
                'message' => 'Category Deleted Successfully!!!',
            ]);
        }
}
