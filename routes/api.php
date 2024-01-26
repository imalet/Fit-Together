<?php

use App\Http\Controllers\Api\CategorieController;
use App\Http\Controllers\Api\CommentaireController;
use App\Http\Controllers\Api\InformationComplementaireController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\SousCategorieController;
use App\Http\Controllers\Api\UserAuthentificationController;
use App\Http\Controllers\Api\UserController;
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
Route::get('/nonConnecte', [UserAuthentificationController::class, 'nonConnecte'])->name('nonConnecte');
Route::post('/logout', [UserAuthentificationController::class, 'logout'])->name('logout')->middleware('auth:api');
Route::post('/refresh', [UserAuthentificationController::class, 'refresh'])->name('refresh')->middleware('auth:api');
Route::post('/updatePassword', [UserAuthentificationController::class, 'updatePassword'])->name('updatePassword')->middleware('auth:api');

// Gestion des Users
Route::get('/users', [UserController::class, 'index'])->name('user.list');
Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show');
Route::post('/user/{id}', [UserController::class, 'update'])->name('user.update')->middleware('auth:api');
Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.delete')->middleware('auth:api');

// Gestion des Informations Complementaires
Route::get('/informations/complementaires', [InformationComplementaireController::class, 'index'])->name('information.complementaire.list');
Route::get('/information/complementaire/{id}', [InformationComplementaireController::class, 'show'])->name('information.complementaire.list.show');
Route::post('/information/complementaire', [InformationComplementaireController::class, 'store'])->name('information.complementaire.list.store')->middleware('auth:api', 'isCoach');
Route::post('/information/complementaire/{id}', [InformationComplementaireController::class, 'update'])->name('information.complementaire.list.update')->middleware('auth:api', 'isCoach');
Route::delete('/information/complementaire/{id}', [InformationComplementaireController::class, 'destroy'])->name('information.complementaire.list.delete')->middleware('auth:api', 'isCoach');

// Gestion des Videos
Route::get('/videos', [VideoController::class, 'index'])->name('video.list');
Route::get('/video/{id}', [VideoController::class, 'show'])->name('video.show');
Route::post('/video', [VideoController::class, 'store'])->name('video.store')->middleware('auth:api','isCoach');
Route::post('/video/{id}', [VideoController::class, 'update'])->name('video.update')->middleware('auth:api', 'isCoach');
Route::delete('/video/{id}', [VideoController::class, 'destroy'])->name('video.delete')->middleware('auth:api', 'isCoach');

// Gestion des Posts
Route::get('/posts', [PostController::class, 'index'])->name('post.list');
Route::get('/post/{id}', [PostController::class, 'show'])->name('post.show');
Route::post('/post', [PostController::class, 'store'])->name('post.store')->middleware('auth:api','isCoach');
Route::post('/post/{id}', [PostController::class, 'update'])->name('post.update')->middleware('auth:api','isCoach');
Route::delete('/post/{id}', [PostController::class, 'destroy'])->name('post.delete')->middleware('auth:api','isCoach');

// Gestion des Commentaires
Route::get('/commentaires', [CommentaireController::class, 'index'])->name('commentaire.list');
Route::get('/commentaire/{id}', [CommentaireController::class, 'show'])->name('commentaire.show');
Route::post('/commentaire', [CommentaireController::class, 'store'])->name('commentaire.store')->middleware('auth:api');
Route::post('/commentaire/{id}', [CommentaireController::class, 'update'])->name('commentaire.update')->middleware('auth:api');
Route::delete('/commentaire/{id}', [CommentaireController::class, 'destroy'])->name('commentaire.delete')->middleware('auth:api');

// Gestion des Sous Categories
Route::get('sous_categories', [SousCategorieController::class, 'index']);
Route::get('sous_categorie/{sousCategorie}', [SousCategorieController::class, 'show']);
Route::post('sous_categorie', [SousCategorieController::class, 'store']);
Route::post('sous_categorie/{sousCategorie}', [SousCategorieController::class, 'update']);
Route::delete('sous_categorie/{sousCategorie}', [SousCategorieController::class, 'destroy']);

// Gestion des Categories
Route::get('categories', [CategorieController::class, 'index']);
Route::get('categorie/{categorie}', [CategorieController::class, 'show']);
Route::post('categorie', [CategorieController::class, 'store']);
Route::post('categorie/{categorie}', [CategorieController::class, 'update']);
Route::delete('categorie/{categorie}', [CategorieController::class, 'destroy']);