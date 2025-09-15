<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/', 'app')->name('home');
Route::view('/veiculos', 'veiculos')->name('veiculos.index');
Route::view('/proprietarios', 'proprietarios')->name('proprietarios.index');
Route::view('/revisoes', 'revisoes')->name('revisoes.index');
Route::view('/relatorios', 'relatorios')->name('relatorios.index');

