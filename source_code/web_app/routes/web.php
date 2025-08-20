<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TradeClientController;
use App\Http\Controllers\TradeMasterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('index');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// Auth
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Delete Post Client Master Connection
    Route::post('/delete/post/client/master/connection', [TradeClientController::class, 'deletePostClientMasterConnection']);

    // updatePostClientMasterConnection
    Route::post('/update/post/client/master/connection', [TradeClientController::class, 'updatePostClientMasterConnection']);

    // Post New Client Master Connection
    Route::post('/postNewClientMasterConnection', [TradeMasterController::class, 'postNewClientMasterConnection'])
        ->name('postNewClientMasterConnection');

    // Update Client
    Route::post('/postUpdateClient', [TradeClientController::class, 'postUpdateClient'])->name('postUpdateClient');

    // Create Client
    Route::post('/postNewClient', [TradeClientController::class, 'postNewClient'])->name('postNewClient');

    // Update Master
    Route::post('/postUpdateMaster', [TradeMasterController::class, 'postUpdateMaster'])->name('postUpdateMaster');

    // Create Master
    Route::post('/postNewMaster', [TradeMasterController::class, 'postNewMaster'])->name('postNewMaster');
    
    // Trade Data
    Route::get('/trade/data', [MainController::class, 'tradeData'])->name('trade-data');

    // Client Accounts
    Route::get('/client/accounts', [MainController::class, 'clientAccounts'])->name('client-accounts');

    // Master Account
    Route::get('/master/accounts', [MainController::class, 'masterAccounts'])->name('master-accounts');

});

require __DIR__.'/auth.php';
