<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PasswordResetController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('index');
// });
// Registration Routes
Route::get('/sign-up', [RegisterController::class, 'create'])->name('register.create');
Route::post('/sign-up', [RegisterController::class, 'store'])->name('register.store');

Route::get('/sign-in', [RegisterController::class, 'createsignin'])->name('register.createsignin');
Route::post('/sign-in', [RegisterController::class, 'authenticate'])->name('login');

Route::get('/admin/dashboard', function () {
    return view('Adminlayouts.dashboard');
})->name('admin.dashboard');

Route::get('/user/dashboard', function () {
    return view('user.dashboard');
})->name('user.dashboard');

Route::get('/tables', [RegisterController::class, 'index'])->name('users.index');

Route::get('/', [ProductController::class, 'index'])->name('user.home');

Route::get('/products/filter', [ProductController::class, 'filter'])->name('products.filter');
//product

Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('admin/dashboard', [ProductController::class, 'store'])->name('products.store');

// A protected dashboard route for after login/registration
Route::get('/dashboard', fn() => 'Welcome to your dashboard!')->middleware('auth');


//cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{productId}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{itemId}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{itemId}', [CartController::class, 'removeAjax'])->name('remove.ajax');


Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');

Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update');