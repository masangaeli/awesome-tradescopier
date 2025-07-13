<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TradeClientController;
use App\Http\Controllers\TradeMasterController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Client Tick Trade Copied
Route::post('/client/post/client/trade/copied', [TradeClientController::class, 'clientPostClientTradeCopied']);

// Get Client Trades to Copy
Route::get('/client/pull/master/trades/list', [TradeClientController::class, 'clientPullMasterTradesList']);

// Get Masters Not Added to Client
Route::get('/pull/client/not/added/masters', [TradeClientController::class, 'pullClientsNotAddedMasters']);

// Post New Trade from Master
Route::post('/post/master/trade/data', [TradeMasterController::class, 'postNewTradeForMaster']);