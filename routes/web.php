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
        Route::match(['get','post'],'ref-accounts/import',[App\Http\Controllers\RefAccountController::class,'import'])->name('ref-accounts.import');
        Route::resource('ref-accounts',App\Http\Controllers\RefAccountController::class);
        Route::get('ref-accounts/download',[App\Http\Controllers\RefAccountController::class,'download'])->name('ref-accounts.download');

        Route::middleware('book_session')->group(function(){
            Route::get('accounts/import',[App\Http\Controllers\AccountController::class,'import'])->name('accounts.import');
            Route::get('accounts/cetak-neraca',[App\Http\Controllers\AccountController::class,'cetakNeraca'])->name('accounts.cetak-neraca');
            Route::get('accounts/cetak-laba-rugi',[App\Http\Controllers\AccountController::class,'cetakLabaRugi'])->name('accounts.cetak-laba-rugi');
            Route::resource('accounts',App\Http\Controllers\AccountController::class);

            Route::get('transactions/cetak-jurnal',[App\Http\Controllers\TransactionController::class,'cetakJurnal'])->name('transactions.cetak-jurnal');
            Route::get('transactions/cetak-buku/{id}',[App\Http\Controllers\TransactionController::class,'cetakBuku'])->name('transactions.cetak-buku');
            Route::resource('transactions',App\Http\Controllers\TransactionController::class);
            Route::delete('transactions/{account_id}/delete',[App\Http\Controllers\TransactionController::class,'delete'])->name('transactions.delete');

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
