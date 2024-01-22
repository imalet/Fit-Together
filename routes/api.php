<?php

use App\Http\Controllers\Api\InformationComplementaireController;
use App\Http\Controllers\Api\UserAuthentificationController;
use App\Http\Controllers\Api\VideoController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Gestion Authentification
Route::post('/login', [UserAuthentificationController::class, 'login'])->name('login');
Route::post('/register', [UserAuthentificationController::class, 'register'])->name('register');
Route::post('/logout', [UserAuthentificationController::class, 'logout'])->name('logout');
Route::post('/refresh', [UserAuthentificationController::class, 'refresh'])->name('refresh');
Route::post('/updatePassword', [UserAuthentificationController::class, 'updatePassword'])->name('updatePassword');

// Gestion des Videos
Route::get('/videos', [VideoController::class, 'index'])->name('video.list')->middleware('auth:api');
Route::post('/video', [VideoController::class, 'store'])->name('video.store');
Route::get('/video/{id}', [VideoController::class, 'show'])->name('video.show');
Route::post('/video/{id}', [VideoController::class, 'update'])->name('video.update');
Route::delete('/video/{id}', [VideoController::class, 'destroy'])->name('video.delete');