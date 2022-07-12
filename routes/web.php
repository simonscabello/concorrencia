<?php

use App\Http\Controllers\ContaController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/conta/deposito', [ContaController::class, 'deposito']);
Route::get('/conta/saldo', [ContaController::class, 'saldo']);
Route::get('/conta/saque', [ContaController::class, 'saque']);
Route::get('/conta/transferir', [ContaController::class, 'transferir']);
