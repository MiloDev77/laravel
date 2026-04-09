<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PublicProductController;
use App\Http\Controllers\GtinController;
use App\Http\Controllers\ReviewController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/product', [PageController::class, 'product'])->middleware('role:admin');
// Route::get('/login', [AuthController::class, 'indexlogin']);
// Route::post('/login', [AuthController::class, 'login']);

// Index
Route::get('/', function () {
    return redirect('/product-list');
});

// JSON API Public
Route::get('/products.json', [ProductController::class, 'indexjson'])->name('products.json');
Route::get('/products/{gtin}.json', [ProductController::class, 'showjson'])->name('products.showjson')->where('gtin', '[0-9]{13,14}');

// Admin Auth
Route::get('/login', [AdminAuthController::class, 'indexlogin'])->name('login');
Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
Route::post('/logout-admin', [AdminAuthController::class, 'logout'])->name('logout.admin');

// Admin Area
Route::middleware('role:admin')->group(function () {
    // Companies
    Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
    Route::get('/companies/create', [CompanyController::class, 'create'])->name('companies.create');
    Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');
    Route::get('/companies/{company}', [CompanyController::class, 'show'])->name('companies.show');
    Route::get('/companies/{company}/edit', [CompanyController::class, 'edit'])->name('companies.edit');
    Route::put('/companies/{company}', [CompanyController::class, 'update'])->name('companies.update');
    Route::patch('/companies/{company}/toggle-active', [CompanyController::class, 'toggleactive'])->name('companies.toggleactive');
    Route::get('/companies/inactive', [CompanyController::class, 'inactive'])->name('companies.inactive');

    // Products
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/new', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product:gtin}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/products/{product:gtin}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product:gtin}', [ProductController::class, 'update'])->name('products.update');
    Route::patch('/products/{product:gtin}/toggle-hidden', [ProductController::class, 'togglehidden'])->name('products.togglehidden');
    Route::delete('/products/{product:gtin}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::delete('/products/{product:gtin}/image', [ProductController::class, 'destroyimage'])->name('products.destroyimage');

    // Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});

// User Auth
Route::get('/register', [UserAuthController::class, 'showregister'])->name('register')->middleware('guest');
Route::post('/register', [UserAuthController::class, 'register'])->name('register.post');
Route::get('/sign-in', [UserAuthController::class, 'showlogin'])->name('user.login')->middleware('guest');
Route::post('/sign-in', [UserAuthController::class, 'login'])->name('user.loginpost');
Route::post('/sign-out', [UserAuthController::class, 'logout'])->name('user.logout');

// User Area
Route::middleware('auth')->group(function () {
    // Edit Profile
    Route::get('/profile/edit', [UserAuthController::class, 'editprofile'])->name('profile.edit');
    Route::put('/profile', [UserAuthController::class, 'updateprofile'])->name('profile.update');

    // Review
    // Route::get('/product-list/{gtin}/reviews', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/products-list/{gtin}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});

// Public
Route::get('/product-list', [PublicProductController::class, 'index'])->name('public.products');
// Route::get('/product-list/{gtin}', [PublicProductController::class, 'show'])->name('public.product');
Route::get('/01/{gtin}', [PublicProductController::class, 'productpage'])->name('public.productpage');
Route::get('/gtin-validate', [GtinController::class, 'show'])->name('gtin.show');
Route::post('/gtin-validate', [GtinController::class, 'validate'])->name('gtin.validate');
