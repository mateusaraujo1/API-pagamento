<?php

use App\Http\Controllers\Api\V1\InvoiceController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TesteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use PHPUnit\Event\Code\Test;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{user}', [UserController::class, 'show']);


//essa linha substitui todas as rotas abaixo dela
Route::apiResource('invoices', InvoiceController::class)
->middleware(['auth:sanctum', 'ability:invoice-store,user-update'])->only(['store', 'update']);
// Route::get('/invoices', [InvoiceController::class, 'index']);
// Route::get('/invoices/{invoice}', [InvoiceController::class, 'show']);
// Route::post('/invoices', [InvoiceController::class, 'store']);
// Route::put('/invoices/{invoice}', [InvoiceController::class, 'update']);
// Route::delete('/invoices/{invoice}', [InvoiceController::class, 'destroy']);


Route::post('/login', [AuthController::class, 'login']);



Route::middleware('auth:sanctum')->group(function () {
    Route::get('/teste', [TesteController::class, 'index'])->middleware('ability:teste-index');
    Route::get('/users/{user}', [UserController::class, 'show'])->middleware('ability:user-get');
    Route::post('/logout', [AuthController::class, 'logout']);
});