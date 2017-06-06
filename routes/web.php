<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

$this->post('register', 'Auth\MyRegisterController@store')->name('register.store');
$this->post('login', 'Auth\MyLoginController@login');

Route::get('auth/{service}', 'Auth\MyLoginController@redirectToProvider');
Route::get('auth/{service}/callback', 'Auth\MyLoginController@handleProviderCallback');
