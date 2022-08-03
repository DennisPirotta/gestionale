<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Auth;
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
Auth::routes();
Route::middleware(['auth'])->group(function (){

/*
*  GESTIONE ROUTES COMMESSE
*/

// Mostra pagina commesse
    Route::get('/commesse', [OrderController::class, 'index']);

// Mostra dettagli singola commessa
    Route::get('/commesse/{order}', [OrderController::class, 'show'])->where('order','[0-9]+');

// Elimina Commessa
    Route::delete('/commesse/{order}' , [OrderController::class, 'destroy']);

// Mostra pagina aggiungi commessa
    Route::get('/commesse/create' , [OrderController::class, 'create']);

// Aggiungi commessa
    Route::post('/commesse' , [OrderController::class, 'store']);

// Mostra pagina modifica commessa
    Route::get('/commesse/{order}/edit', [OrderController::class, 'edit']);

// Salva modifiche commessa
    Route::put('/commesse/{order}', [OrderController::class, 'update']);

    /*
    *  GESTIONE ROUTES UTENTI
    */

    // Sezione utenti
    Route::get('/', [HomeController::class, 'index']);


    /*
    *  GESTIONE ROUTES CLIENTI
    */

    // Mostra pagina clienti
    Route::get('/clienti', [CustomerController::class, 'index']);

    // Mostra pagina crea cliente
    Route::get('/clienti/create',[CustomerController::class, 'create']);

    // Mostra pagina clienti
    Route::post('/clienti', [CustomerController::class, 'store']);

    // Mostra pagina modifica
    Route::get('/clienti/{customer}/edit',[CustomerController::class,'edit']);

    // Modifica cliente
    Route::put('/clienti/{customer}', [CustomerController::class, 'update']);

    // Elimina cliente
    Route::delete('/clienti/{customer}',[CustomerController::class, 'destroy']);

    /*
    *  GESTIONE ROUTES FERIE
    */

    Route::get('/ferie',[HolidayController::class, 'index']);
});

