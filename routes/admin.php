<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\FamilyController;
use App\Http\Controllers\admin\SubCategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.dashboard');
})->name('dashboard');

Route::resource(
    'families', FamilyController::class,
);
Route::resource(
    'categories', CategoryController::class,
);
Route::resource(
    'subCategories', SubCategoryController::class,
);
