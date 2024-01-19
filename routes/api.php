<?php

use App\Http\Controllers\Api\InformationComplementaireController;
use App\Http\Controllers\Api\UserAuthentificationController;
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

Route::post('/login', [UserAuthentificationController::class, 'login'])->name('login');
Route::post('/register', [UserAuthentificationController::class, 'register'])->name('register');
Route::get('/logout', [UserAuthentificationController::class, 'logout'])->name('logout');