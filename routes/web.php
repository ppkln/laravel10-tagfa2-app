<?php

use App\Http\Controllers\AlbumProductsController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\sponsorscontroller;
use App\Http\Controllers\ManageUserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// })->name('welcome');
Route::get('/',[ProductsController::class,'welcome'])->name('welcome');
Route::get('/detailsProduct/{id}',[ProductsController::class,'detailsProduct']);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        $users = User::paginate(3);
        return view('dashboard',compact('users'));
    })->name('dashboard');
});

Route::middleware(['auth:sanctum',config('jetstream.auth_session'),'verified'])->group(function (){
    // ส่วนของ manage user
    Route::get('/manageUser/edit/{id}',[ManageUserController::class,'editPriority']);
    Route::post('/manageUser/update/{id}',[ManageUserController::class,'updatePriority']);
    // ส่วนของ Product
    Route::get('/products',[ProductsController::class,'index'])->name('productslist');
    Route::post('/products/add',[ProductsController::class,'insertProduct'])->name('addProduct');
    Route::get('/products/edit/{id}',[ProductsController::class,'editProduct']);
    Route::post('/products/update/{id}',[ProductsController::class,'updateProduct']);
    Route::get('/products/del/{id}',[ProductsController::class,'delProduct']);
    // ส่วนของ Album ที่ระบุของแต่ละ Product
    Route::get('/albumproducts/album/{id}',[AlbumProductsController::class,'albumQueryId']);
    Route::post('/albumproducts/add',[AlbumProductsController::class,'albumAddImg'])->name('albumAddImg');
    Route::get('/albumproducts/deleteImg/{id}',[AlbumProductsController::class,'deleteImg']);
    // ส่วนของ sponsor
    Route::get('/sponsors',[sponsorscontroller::class,'index'])->name('sponsors');
    Route::post('/sponsors/add',[sponsorscontroller::class,'insertSponsor'])->name('addSponsor');
    Route::get('/sponsors/edit/{id}',[sponsorscontroller::class,'editSponsor']);
    Route::post('/sponsors/update/{id}',[sponsorscontroller::class,'updateSponsor']);
    Route::get('/sponsors/del/{id}',[sponsorscontroller::class,'deleteSponsor']);
});

