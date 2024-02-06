<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


// GET /: Página inicial que lista todas as questões.
Route::get('/questions', 'QuestionController@index')->name('questions.index'); 

// GET /questions/{question}: Exibe os detalhes de uma questão específica. 
Route::get('/questions/{question}', 'QuestionController@show')->name('questions.show');

// GET /questions/language/{language}: Lista todas as questões associadas a uma linguagem específica. Por exemplo, se você quiser listar todas as questões de PHP, a URL seria /questions/language/PHP
Route::get('/questions/language/{language}', 'QuestionController@byLanguage')->name('questions.byLanguage');

// GET /questions/language/{language}/{question}: Exibe uma questão específica de uma linguagem. Por exemplo, para ver uma questão específica de PHP, a URL seria /questions/language/PHP/1, onde "1" é o ID da questão.
Route::get('/questions/language/{language}/{question}', 'QuestionController@showByLanguage')->name('questions.showByLanguage');
