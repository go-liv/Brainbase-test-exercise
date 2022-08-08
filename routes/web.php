<?php
use App\Http\Controllers\CryptoController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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

//Main page access calls CryptoController to retrieve crypto data
Route::get("/", [CryptoController::class, 'getCrypto']);