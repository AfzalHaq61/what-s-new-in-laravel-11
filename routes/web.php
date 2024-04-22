<?php

use App\Car;
use App\Models\User;
use App\Services\MemoizationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/', function (Request $request) {

    dump($request->identifier());
    dump($request->identifier());
    dump($request->identifier());

    $service = new MemoizationService();

    return $service->getResult();

    return view('welcome')->name('dashboard');
});
