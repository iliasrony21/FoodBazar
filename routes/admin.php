<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Route::get('/notAdmin', function () {
//     return 'You Are not Admin';
// })->name('notAdmin');
// Route::get('/check', [HomeController::class, 'check'])->name('check.admin')->middleware('is_admin');

Route::middleware('admin')->prefix('admin')->group(function(){
    Route::get('/dashboard',[AdminController::class,'adminDashboard'])->name('admin.dashboard');
    Route::get('/new',[AdminController::class,'new'])->name('admin.new');

    // category part start
    Route::prefix('category')->group(function(){
        Route::get('/',[CategoryController::class,'index'])->name('category');
        Route::get('/edit/{id}',[CategoryController::class,'edit'])->name('category.edit');
        Route::delete('/delete/{id}',[CategoryController::class,'destroy'])->name('category.delete');
        Route::post('/store',[CategoryController::class,'store'])->name('category.store');
        Route::post('/update',[CategoryController::class,'update'])->name('category.update');

    });
    // category part end



});
Route::prefix('admin')->group(function(){
    Route::get('login',[AdminController::class,'adminLogin'])->name('admin.login');
    Route::post('store',[AdminController::class,'adminStore'])->name('admin.store');
    Route::get('logout',[AdminController::class,'adminLogout'])->name('admin.logout');
});
