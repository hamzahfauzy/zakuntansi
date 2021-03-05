<?php

use App\Models\RefAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('ref-account')->group(function(){
    Route::get('/',function(){
        return RefAccount::get();
    });

    Route::get('{account_code}',function($account_code){
        return RefAccount::where('account_code',$account_code)->firstOrFail();
    });
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
