<?php

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

Route::middleware('installed')->group(function(){
    Auth::routes([
        'register' => false,
        'reset' => false, 
        'verify' => false
    ]);

    Route::middleware('auth')->group(function(){
        Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

        Route::resource('books',App\Http\Controllers\BookController::class);
        Route::resource('ref-accounts',App\Http\Controllers\RefAccountController::class);
        Route::match(['get','post'],'ref-accounts/import',[App\Http\Controllers\RefAccountController::class,'import'])->name('ref-accounts.import');
        Route::get('ref-accounts/download',[App\Http\Controllers\RefAccountController::class,'download'])->name('ref-accounts.download');

        Route::middleware('book_session')->group(function(){
            Route::get('accounts/import',[App\Http\Controllers\AccountController::class,'import'])->name('accounts.import');
            Route::resource('accounts',App\Http\Controllers\AccountController::class);

            Route::resource('transactions',App\Http\Controllers\TransactionController::class);

            Route::get('buku-besar',[App\Http\Controllers\TransactionController::class,'bukuBesar'])->name('buku-besar');
            Route::get('neraca',[App\Http\Controllers\AccountController::class,'neraca'])->name('neraca');
            Route::get('laba-rugi',[App\Http\Controllers\AccountController::class,'labaRugi'])->name('laba-rugi');
            
            Route::post('accounts/insert',[App\Http\Controllers\AccountController::class,'insert'])->name('accounts.insert');
            
        });
    });
    
});

Route::middleware('installation')->group(function(){
    Route::match(['get','post'], 'installation', [App\Http\Controllers\HomeController::class, 'installation'])->name('installation');
});
