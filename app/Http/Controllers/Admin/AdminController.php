<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function adminDashboard(){
        return view('admin.index');
    }
    public function adminLogin(){
        return view('admin.login');
    }
    public function adminLogout(){
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login')->with('success','LogOut Successfully');
    }
    public function adminStore(Request $request){
        $check = $request->all();
        $data =[
          'email' => $check['email'],
          'password' => $check['password'],
        ];
        if(Auth::guard('admin')->attempt($data)){
            return redirect()->route('admin.dashboard')->with('success','Login Successful');

        }
        else{
            return redirect()->route('admin.login')->with('error','You are not a Admin');

        }
    }

    public function new(){
        return view('Backend.new');
    }
}
