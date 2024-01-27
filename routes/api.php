<?php

use App\Http\Controllers\CreateInvoiceController;
use App\Http\Controllers\CustomerController;
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


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/invoices/create-invoices', [CreateInvoiceController::class, 'store']);
Route::post('/add-customers', [CustomerController::class, 'store']);
Route::get('/get-customers-by-id/{id}', [CustomerController::class, 'getById']);
Route::put('/invoices/update-invoice-status-by-id/{id}', [CreateInvoiceController::class, 'updateStatus']);
Route::get('/get-all-invoices', [CreateInvoiceController::class, 'getAllInvoices']);

Route::post('/user/blacklist/{id}', [CustomerController::class, 'addToBlacklist']);
Route::post('/user/remove-blacklist/{id}', [CustomerController::class, 'removeFromBlacklist']);
Route::get('/user/blacklist-status/{id}', [CustomerController::class, 'getBlacklistStatus']);

Route::get('/get-all-customers', [CustomerController::class, 'getAllCustomers']);
//Route::get('/get-all-blacklisted-customers', [CustomerController::class, 'getAllBlacklistedCustomers']);
//Route::get('/get-all-non-blacklisted-customers', [CustomerController::class, 'getAllNonBlacklistedCustomers']);
Route::get('/get-all-customers-filter-by-blacklist', [CustomerController::class, 'getCustomersFilterByBlacklistORNot']);
Route::put('/customer/update/{id}', [CustomerController::class,'update']);
Route::delete('/customer-delete/{id}', [CustomerController::class,'delete']);
Route::delete('/customer-remove-from-db/{id}', [CustomerController::class,'deleteFromDb']);




