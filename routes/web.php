<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/token', function () {
    return csrf_token();
});

Route::post('/login', function (Request $request) {
    return response()->json(['message' => 'Login endpoint']);
})->name('login');

Route::get('/greeting', function () {
    return 'Hello World';
});
