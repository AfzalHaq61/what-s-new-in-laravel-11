<?php

use App\Car;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    $car = new Car('Toyota', 'Camry', 2022);
    $car->dump();

    User::latest()->limit(5)->dump()->get();

    return view('welcome')->name('dashboard');
});
