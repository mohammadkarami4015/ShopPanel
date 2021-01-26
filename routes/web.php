<?php

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


use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\ProductCommentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ShopCategoryController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');

Route::prefix('auth')->group(function () {

    Route::get('/send-number', [LoginController::class, 'sendNumber'])->name('sendNumber');
    Route::get('/login', [LoginController::class, 'login'])->name('login');
});

Auth::routes();


Route::middleware('auth:web')->group(function () {

    Route::get('/dashboard', function () {

        return view('dashboard');

    })->name('dashboard');


    /****************************************************************SHOP ROUTES***********************************************/


    Route::prefix('/shop')->group(function () {

        Route::get('/', [ShopController::class, 'details'])->name('shop.details');

        Route::get('/edit/{shop}', [ShopController::class, 'edit'])->name('shop.edit');

        Route::patch('/{shop}', [ShopController::class, 'update'])->name('shop.update');

        Route::delete('/delete-photo/{shop}', [ShopController::class, 'deletePhoto'])->name('shop.delete-photo');

        Route::delete('/delete-sendPrice/{shop}', [ShopController::class, 'deleteSendPrice'])->name('shop.delete-sendPrice');

        Route::post('/create-sendPrice/{shop}', [ShopController::class, 'createSendPrice'])->name('shop.create-sendPrice');

        Route::post('/add-photo/{shop}', [ShopController::class, 'addPhoto'])->name('shop.add-photo');

        Route::post('/add-logo/{shop}', [ShopController::class, 'addLogo'])->name('shop.add-logo');

        Route::post('/working-hours/{shop}', [ShopController::class, 'workingHour'])->name('shop.workingHour');

        Route::post('/latLang/{shop}', [ShopController::class, 'updateLatLang'])->name('shop.latLang');
    });


    /****************************************************************MESSAGES ROUTES***********************************************/


    Route::prefix('message')->group(function () {

        Route::get('/', [ShopController::class, 'messages'])->name('message.index');

        Route::get('/create', [ShopController::class, 'createMessage'])->name('message.create');

        Route::post('/', [ShopController::class, 'storeMessage'])->name('message.store');
    });


    /****************************************************************SHOP_CATEGORY ROUTES***********************************************/

    Route::get('/shopCategory-search', [ShopCategoryController::class, 'search'])->name('shopCategory.search');

    Route::resource('shopCategory', 'ShopCategoryController');

    /****************************************************************PRODUCT ROUTE***********************************************/


    Route::get('/product-search', [ProductController::class, 'search'])->name('product.search');

    Route::post('/product-features/{product}',[ProductController::class,'addFeature'])->name('product.addFeatures');

    Route::delete('/product-features/{product}',[ProductController::class,'deleteFeature'])->name('product.deleteFeatures');

    Route::post('/product-photo/{product}', [ProductController::class, 'addPhoto'])->name('product.add-photo');

    Route::delete('/product-Photo/{product}', [ProductController::class, 'deletePhoto'])->name('product.delete-photo');

    Route::resource('product', 'ProductController');


    /****************************************************************PRODUCT_COMMENT ROUTE***********************************************/

    Route::prefix('/productComment/{product}')->group(function () {

        Route::get('/', [ProductCommentController::class, 'index'])->name('productComment.index');

        Route::get('/reply/{productComment}', [ProductCommentController::class, 'create'])->name('productComment.create');

        Route::post('/reply/{productComment}', [ProductCommentController::class, 'reply'])->name('productComment.reply');

        Route::delete('/reply/{productComment}', [ProductCommentController::class, 'destroy'])->name('productComment.destroy');

        Route::get('/verify/{id}/{value}', [ProductCommentController::class, 'verify'])->name('productComment.verify');
    });


    /****************************************************************ORDER ROUTE***********************************************/

    Route::prefix('order')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('order.index');
        Route::get('/show/{order}', [OrderController::class, 'show'])->name('order.show');
        Route::get('/edit/{order}', [OrderController::class, 'edit'])->name('order.edit');
        Route::get('/print/{order}', [OrderController::class, 'print'])->name('order.print');
        Route::patch('/{order}', [OrderController::class, 'update'])->name('order.update');
    });

    Route::get('order-search', [OrderController::class, 'search'])->name('order.search');


    /****************************************************************DISCOUNT ROUTE***********************************************/

    Route::get('/discount-search', [DiscountController::class, 'search'])->name('discount.search');

    Route::resource('discount', 'DiscountController');

    /****************************************************************PAYMENT ROUTE***********************************************/
    Route::prefix('payment')->group(function () {

        Route::get('', [PaymentsController::class, 'index'])->name('payment.index');
        Route::get('/{payment}', [PaymentsController::class, 'show'])->name('payment.show');
    });
    /****************************************************************REPORT ROUTE***********************************************/
    Route::get('/report', [ReportsController::class, 'index'])->name('report.index');
});
