<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\MutationController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Models\Product;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('v1')->group(function () {
    // Route untuk login dan register
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register'])->name('register');

    // Route yang dilindungi dengan middleware auth
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('profile', [AuthController::class, 'profile'])->name('profile');

        // Grouping dengan route dashboard
        Route::prefix('dashboard')->group(function() {  
            // Route untuk manajemen Product atau Barang
            Route::prefix('products')->name('products.')->group(function () {
                Route::get('/', [ProductController::class, 'index'])->name('index'); // GET /products
                Route::post('/', [ProductController::class, 'store'])->name('store'); // POST /products
                Route::get('/{product}', [ProductController::class, 'show'])->name('show'); // GET /products/{product}
                Route::put('/{product}', [ProductController::class, 'update'])->name('update'); // PUT /products/{product}
                Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy'); // DELETE /products/{product}
            });
            
            // Route untuk manajemen Mutation atau Mutasi
            Route::prefix('mutations')->name('mutations.')->group(function () {
                Route::get('/', [MutationController::class, 'index'])->name('index'); // GET /mutations
                Route::post('/', [MutationController::class, 'store'])->name('store'); // POST /mutations
                Route::get('/{mutation}', [MutationController::class, 'show'])->name('show'); // GET /mutations/{mutation}
                Route::put('/{mutation}', [MutationController::class, 'update'])->name('update'); // PUT /mutations/{mutation}
                Route::delete('/{mutation}', [MutationController::class, 'destroy'])->name('destroy'); // DELETE /mutations/{mutation}
            });
        });
    });
});
