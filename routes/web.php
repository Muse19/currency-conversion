<?php

use App\Http\Controllers\CurrencyConversionController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [CurrencyConversionController::class, 'index']);

Route::get('/convert', [CurrencyConversionController::class, 'convertCurrency'])
    ->middleware('throttle:currency-convert');

Route::view('limit-reached', 'errors.limit-reached');
