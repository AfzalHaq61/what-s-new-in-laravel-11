<?php

use App\Car;
use App\Models\User;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Client\ConnectionException;

Route::get('/', function () {
    try {
        $conversionRates = Http::pool(fn (Pool $pool) => [
            $pool->as('GBP')->retry(3)->get("http://flaky.test/api/conversion/GBP"),
            $pool->as('USD')->retry(3)->get("http://flaky.test/api/conversion/USD"),
            $pool->as('EUR')->retry(3)->get("http://flaky.test/api/conversion/EUR"),
        ]);
    } catch (ConnectionException $e) {
        // Handle connection error
        return response()->json(['error' => 'Connection error'], 500);
    }

    return collect($conversionRates)->map(fn (Response $response) => $response->body());
});
