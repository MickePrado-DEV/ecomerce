<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\FamilyController;
use App\Http\Controllers\Admin\OptionController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\admin\SubCategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.dashboard');
})->name('dashboard');


Route::get(
    '/options',
    [OptionController::class, 'index']
)->name('options.index');
Route::resource(
    'families',
    FamilyController::class,
);
Route::resource(
    'categories',
    CategoryController::class,
);
Route::resource(
    'subCategories',
    SubCategoryController::class,
);
Route::resource(
    'products',
    ProductController::class,
);


Route::get(
    'products/{product}/variants/{variant}',
    [ProductController::class, 'variants']
)->name('products.variants')
    ->scopeBindings();
