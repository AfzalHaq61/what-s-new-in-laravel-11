<?php

use App\Car;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    return User::with('latestPosts')->get();

    return view('welcome')->name('dashboard');
});
