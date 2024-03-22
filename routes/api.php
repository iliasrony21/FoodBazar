<?php

use App\Http\Controllers\Api\AdminApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('admin',[AdminApiController::class,'AdminLogin']);
Route::get('admin/dashboard',[AdminApiController::class,'AdminDashboard'])->middleware(['auth:sanctum', 'abilities:admin-api']);


