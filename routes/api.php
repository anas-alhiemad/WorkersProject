<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PostController;

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

Route::group([
//'middleware' => '',
'prefix' => 'auth/admin'
],function($router){
Route::post('/login', [AdminController::class, 'login']);
Route::post('/register', [AdminController::class, 'register']);
Route::post('/logout', [AdminController::class, 'logout']);
Route::post('/refresh', [AdminController::class, 'refresh']);
Route::get('/userProfile', [AdminController::class, 'userProfile']);
});



Route::group([
    //'middleware' => '',
    'prefix' => 'auth/worker'
    ],function($router){
    Route::post('/login', [WorkerController::class, 'login']);
    Route::post('/register', [WorkerController::class, 'register']);
    Route::post('/logout', [WorkerController::class, 'logout']);
    Route::post('/refresh', [WorkerController::class, 'refresh']);
    Route::get('/userProfile', [WorkerController::class, 'userProfile']);
    Route::get('/verify/{token}', [WorkerController::class, 'verify']);
    });


Route::group([
    //'middleware' => '',
    'prefix' => 'worker/post'
    ],function(){
    Route::post('/add', [PostController::class, 'store'])->middleware('auth:worker');
    });


    Route::group([
        //'middleware' => '',
        'prefix' => 'auth/client'
        ],function($router){
        Route::post('/login', [ClientController::class, 'login']);
        Route::post('/register', [ClientController::class, 'register']);
        Route::post('/logout', [ClientController::class, 'logout']);
        Route::post('/refresh', [ClientController::class, 'refresh']);
        Route::get('/userProfile', [ClientController::class, 'userProfile']);
        });









        Route::get('/Unauthorized',function(){
            return response()->json(["Message" => "Unauthorized"], 401);
        })->name('login');



        