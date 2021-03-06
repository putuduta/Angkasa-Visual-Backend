<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DesignerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });



Route::middleware(['cors'])->group(function () {
    // Product
    Route::get('/product/{id}', [ProductController::class, 'findProductById']);
    Route::get('/products', [ProductController::class, 'index']);

    Route::get('/finddesignerbyproduct/{id}', [DesignerController::class, 'findDesignerByProduct']);

    // Auth
    Route::post('/login', [ApiController::class, 'authenticate']);
    Route::post('/register', [ApiController::class, 'register']);
    Route::post('/registerdesigner', [ApiController::class, 'registerDesigner']);


    Route::group(['middleware' => ['jwt.verify']], function () {

        // Designer
        Route::post('/updatedesigner', [DesignerController::class, 'update']);
        Route::post('/getdesigners', [DesignerController::class, 'getdesigners']);

        // User Auth
        Route::post('/logout', [ApiController::class, 'logout']);
        Route::post('/getuser', [ApiController::class, 'get_user']);

        // Cart
        Route::post('/carts', [CartController::class, 'index']);
        Route::post('/deletecart', [CartController::class, 'delete']);
        Route::post('/savecart', [CartController::class, 'save']);

        // Transaction
        Route::post('/saveorder', [TransactionController::class, 'save']);
        Route::post('/getorderbyorderid/{id}', [TransactionController::class, 'getOrderByOrderId']);
        Route::post('/getorderlist', [TransactionController::class, 'getOrderList']);

        // Chat
        Route::post('/chats', [ChatController::class, 'index']);
        Route::post('/savecart', [ChatController::class, 'save']);
    });
});
