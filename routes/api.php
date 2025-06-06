<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\blogController;
use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// register
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// blog
Route::get('/blog', [blogController::class, 'index']);
Route::get('/blog/{slug}', [blogController::class, 'show']);
// get all categories
Route::get('/blogs/category', [CategoryController::class, 'getAllCategories']);

Route::get('/blog/categories', function () {
    return response()->json(['message' => 'route works']);
});

// blog by category
Route::get('/blog/category/{name}', [CategoryController::class, 'getPostByCategoryName']);


Route::get('/coba/blabla', function () {
    return response()->json(['message' => 'route works']);
});

