<?php

use Illuminate\Http\Request;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\LanguageController;
use App\Http\Controllers\API\QuestionController;

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

// Rotas de autenticação
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Rotas com user autenticado
Route::middleware('auth:api')->group(function () {
    Route::get('user', [AuthController::class, 'user']);
    Route::post('logout', [AuthController::class, 'logout']);
    
    Route::middleware('isAdmin')->group(function () {
        Route::prefix('languages')->group(function () {
            Route::controller(LanguageController::class)->group(function () {
                Route::post('/', 'store');
                Route::delete('/{language}', 'destroy');
            });
        });
    
        Route::prefix('categories')->group(function () {
            Route::controller(CategoryController::class)->group(function () {
                Route::post('/', 'store');
                Route::delete('/{category}', 'destroy');
            });
        });
    
        Route::prefix('questions')->group(function () {
            Route::controller(QuestionController::class)->group(function () {
                Route::post('/', 'store');
                Route::delete('/{question}', 'destroy');
            });
        });
    });
});

// Languages
// Route::resource('languages', LanguageController::class)->except(['create', 'edit']);
Route::prefix('languages')->group(function () {
    Route::controller(LanguageController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/{language}', 'show');
        Route::get('/{languageId}/questions', [QuestionController::class, 'getQuestionsByLanguage']);
    });
});

// Categories
// Route::resource('categories', CategoryController::class)->except(['create', 'edit']);
Route::prefix('categories')->group(function () {
    Route::controller(CategoryController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/{category}', 'show');
    });
});

// Questions
// Route::resource('questions', QuestionController::class)->except(['create', 'edit']);
Route::prefix('questions')->group(function () {
    Route::controller(QuestionController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
    });
});