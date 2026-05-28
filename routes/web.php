<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'quotation.index');
Route::view('/login', 'quotation.index')->name('login');
