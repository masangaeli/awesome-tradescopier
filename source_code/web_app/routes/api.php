<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TradeClientController;
use App\Http\Controllers\TradeMasterController;
use App\Http\Controllers\TradeDataController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Delete All Trade Data
Route::get('/delete/all/trade/data', [TradeDataController::class, 'deleteAllTradeData']);

// Delete Master Account
Route::get('/delete/master/account', [TradeMasterController::class, 'deleteMasterAccount']);

// Delete Trade Data
Route::get('/delete/trade/data', [TradeDataController::class, 'deleteTradeData']);

// Delete Client Account
Route::get('/delete/client/account', [TradeClientController::class, 'deleteClientAccount']);

// Client Tick Trade Copied
Route::post('/client/post/client/trade/copied', [TradeClientController::class, 'clientPostClientTradeCopied']);

// Get Client Trades to Copy
Route::get('/client/pull/master/trades/list', [TradeClientController::class, 'clientPullMasterTradesList']);

// Get Masters Not Added to Client
Route::get('/pull/client/not/added/masters', [TradeClientController::class, 'pullClientsNotAddedMasters']);

// Post New Trade from Master
Route::post('/post/master/trade/data', [TradeMasterController::class, 'postNewTradeForMaster']);