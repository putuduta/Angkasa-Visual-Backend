<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DesignerController;
use App\Http\Controllers\ProductController;

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
    Route::get('/product/{id}', [ProductController::class, 'findProductById']);
    Route::get('/products', [ProductController::class, 'index']);
    
    Route::get('/finddesignerbyproduct/{id}', [DesignerController::class, 'findDesignerByProduct']);

    Route::post('/login', [ApiController::class, 'authenticate']);
    Route::post('/register', [ApiController::class, 'register']);
    Route::post('/registerdesigner', [ApiController::class, 'registerDesigner']);

    
    Route::group(['middleware' => ['jwt.verify']], function() {

        Route::post('/updatedesigner', [DesignerController::class, 'update']);
        Route::post('/getdesigners', [DesignerController::class, 'getdesigners']);
    
    
        Route::post('/savecart', [CartController::class, 'save']);

        Route::post('/logout', [ApiController::class, 'logout']);
        Route::post('/getuser', [ApiController::class, 'get_user']);

        Route::post('/carts', [CartController::class, 'index']);
    });

});