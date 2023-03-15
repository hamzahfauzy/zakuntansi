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
        Route::get('/home', [App\Http\Controllers\HomeController::class, 'index']);

        Route::middleware('role:Operator')->group(function(){
            Route::resource('categories',App\Http\Controllers\CategoryController::class);
            Route::resource('merchants',App\Http\Controllers\MerchantController::class);
            Route::resource('study-groups',App\Http\Controllers\StudyGroupController::class);
            Route::match(['get','post'],"students/import",[App\Http\Controllers\StudentController::class,'import'])->name("students.import");
            Route::get("students/export",[App\Http\Controllers\StudentController::class,'export'])->name("students.export");
            Route::resource('students',App\Http\Controllers\StudentController::class);
            Route::match(['get','post'],"teachers/import",[App\Http\Controllers\TeacherController::class,'import'])->name("teachers.import");
            Route::resource('teachers',App\Http\Controllers\TeacherController::class);
        });

        Route::middleware('role:Bendahara')->group(function(){
            Route::match(['get','post'],'bills/import',[App\Http\Controllers\BillController::class,'import'])->name("bills.import");
            Route::get('bills/export',[App\Http\Controllers\BillController::class,'export'])->name("bills.export");
            Route::get('bills/notif/{id}',[App\Http\Controllers\BillController::class,'notif'])->name('bills.notif');
            Route::resource('bills',App\Http\Controllers\BillController::class);
            Route::resource('finances',App\Http\Controllers\FinanceController::class);

            Route::group(['prefix'=>'reports','as'=>'reports.'],function(){
                Route::get('',[App\Http\Controllers\ReportController::class,'index'])->name('index');
            });

        });

        Route::middleware('role:Kasir')->group(function(){
            Route::get('payments/cetak/{user}/{date}',[App\Http\Controllers\PaymentController::class,'cetak'])->name("payments.cetak");
            Route::match(['get','post'],'payments/import',[App\Http\Controllers\PaymentController::class,'import'])->name("payments.import");
            Route::get('payments/export',[App\Http\Controllers\PaymentController::class,'export'])->name("payments.export");
            Route::resource('payments',App\Http\Controllers\PaymentController::class);
        });

        Route::middleware('role:Master')->group(function(){
            Route::get('/home/count-transaction/{transaction_code}/{month}', [App\Http\Controllers\HomeController::class, 'count_transaction'])->name('count_transaction');
            
            Route::resource('books',App\Http\Controllers\BookController::class);
            Route::match(['get','post'],'ref-accounts/import',[App\Http\Controllers\RefAccountController::class,'import'])->name('ref-accounts.import');
            Route::resource('ref-accounts',App\Http\Controllers\RefAccountController::class);
            Route::get('ref-accounts/download',[App\Http\Controllers\RefAccountController::class,'download'])->name('ref-accounts.download');
            
            Route::post('accounts/import',[App\Http\Controllers\AccountController::class,'import'])->name('accounts.import');
            Route::get('accounts/cetak-neraca',[App\Http\Controllers\AccountController::class,'cetakNeraca'])->name('accounts.cetak-neraca');
            Route::get('accounts/cetak-laba-rugi',[App\Http\Controllers\AccountController::class,'cetakLabaRugi'])->name('accounts.cetak-laba-rugi');
            Route::resource('accounts',App\Http\Controllers\AccountController::class);
            
            Route::resource('category-type-accounts',App\Http\Controllers\CategoryTypeAccountController::class);
            Route::resource('users',App\Http\Controllers\UserController::class);
            Route::resource('roles',App\Http\Controllers\RoleController::class);
            Route::resource('permissions',App\Http\Controllers\PermissionController::class);

            Route::prefix('transactions')->name('transactions.')->group(function(){
                Route::get('/',[App\Http\Controllers\TransactionController::class,'index'])->name('index');
                Route::get('create',[App\Http\Controllers\TransactionController::class,'create'])->name('create');
                Route::get('edit/{transaction}',[App\Http\Controllers\TransactionController::class,'edit'])->name('edit');
                Route::post('store',[App\Http\Controllers\TransactionController::class,'store'])->name('store');
                Route::patch('update/{transaction}',[App\Http\Controllers\TransactionController::class,'update'])->name('update');
                Route::delete('destroy/{transaction}',[App\Http\Controllers\TransactionController::class,'destroy'])->name('destroy');
                Route::get('cetak-jurnal',[App\Http\Controllers\TransactionController::class,'cetakJurnal'])->name('cetak-jurnal');
                Route::get('cetak-buku',[App\Http\Controllers\TransactionController::class,'cetakBuku'])->name('cetak-buku');
                Route::get('export-buku',[App\Http\Controllers\TransactionController::class,'exportBuku'])->name('export-buku');
                // Route::resource('transactions',App\Http\Controllers\TransactionController::class);
                Route::delete('{account_id}/delete',[App\Http\Controllers\TransactionController::class,'delete'])->name('delete');
            });

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
