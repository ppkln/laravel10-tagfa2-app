<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\ProductsController;

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

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

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
    Route::get('/products',[ProductsController::class,'index'])->name('productslist');
    Route::post('/products/add',[ProductsController::class,'insertProduct'])->name('addProduct');
    Route::get('/products/edit/{id}',[ProductsController::class,'editProduct']);
    Route::post('/products/update/{id}',[ProductsController::class,'updateProduct']);
});

