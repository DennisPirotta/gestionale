<?php

use App\Http\Controllers\AccessKeyController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HourController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TechnicalReportDetailsController;
use App\Http\Controllers\UserController;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
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
App::setLocale('it');
Lang::setLocale('it');
Auth::routes();
Auth::routes(['verify' => true]);
Route::middleware(['auth'])->group(function () {

    /*
    *  GESTIONE ROUTES COMMESSE
    */

    // Mostra pagina commesse
    Route::get('/commesse', [OrderController::class, 'index'])->name('orders.index');

    // Mostra dettagli singola commessa
    Route::get('/commesse/{order}', [OrderController::class, 'show'])->where('order', '[0-9]+')->name('orders.show');

    // Elimina Commessa
    Route::delete('/commesse/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

    // Mostra pagina aggiungi commessa
    Route::get('/commesse/create', [OrderController::class, 'create'])->name('orders.create');

    // Aggiungi commessa
    Route::post('/commesse', [OrderController::class, 'store'])->name('orders.store');

    // Mostra pagina modifica commessa
    Route::get('/commesse/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');

    // Salva modifiche commessa
    Route::put('/commesse/{order}', [OrderController::class, 'update'])->name('orders.update');

    // Mostra report commesse
    Route::get('/commesse/report', [OrderController::class, 'report'])->name('orders.report');

    /*
    *  GESTIONE ROUTES HOME
    */

    // Mostra pagina principale
    Route::get('/', [HomeController::class, 'index'])->name('home.index');

    // Rimuovi popup del primo login
    Route::post('/', [HomeController::class,'store'])->name('home.store');

    /*
    *  GESTIONE ROUTES CLIENTI
    */

    // Mostra pagina clienti
    Route::get('/clienti', [CustomerController::class, 'index'])->name('customers.index');

    // Mostra pagina crea cliente
    Route::get('/clienti/create', [CustomerController::class, 'create'])->name('customers.create');

    // Mostra pagina clienti
    Route::post('/clienti', [CustomerController::class, 'store'])->name('customers.store');

    // Mostra pagina modifica
    Route::get('/clienti/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');

    // Modifica cliente
    Route::put('/clienti/{customer}', [CustomerController::class, 'update'])->name('customers.update');

    // Elimina cliente
    Route::delete('/clienti/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    /*
    *  GESTIONE ROUTES FERIE
    */

    // Mostra pannello con ferie rimanenti e calendario
    Route::get('/ferie', [HolidayController::class, 'index'])->name('holidays.index');

    // Mostra pagina crea evento
    Route::get('/ferie/create', [HolidayController::class, 'create'])->name('holidays.create');

    // Crea ferie
    Route::post('/ferie', [HolidayController::class, 'store'])->name('holidays.store');

    // Mostra pagina modifica
    Route::get('/ferie/{holiday}/edit', [HolidayController::class, 'edit'])->name('holidays.edit');

    // Modifica cliente
    Route::put('/ferie/{holiday}', [HolidayController::class, 'update'])->name('holidays.update');

    // Approva ferie
    Route::put('/ferie/approve/{holiday}', [HolidayController::class, 'approve'])->name('holidays.approve');

    // Elimina cliente
    Route::delete('/ferie/{holiday}', [HolidayController::class, 'destroy'])->name('holidays.destroy');

    // Elimina cliente multiplo
    Route::post('/ferie/delete', [HolidayController::class, 'destroyMore'])->name('holidays.destroyMore');


    /*
    *  GESTIONE ROUTES ORE
    */

    // Mostra pannello ore
    Route::get('/ore', [HourController::class, 'index'])->name('hours.index');

    // Mostra pagina inserisci ore
    Route::get('/ore/create', [HourController::class, 'create'])->name('hours.create');

    // Inserisci ore
    Route::post('/ore', [HourController::class, 'store'])->name('hours.store');

    // Mostra pagina modifica
    Route::get('/ore/{hour}/edit', [HourController::class, 'edit'])->name('hours.edit');

    // Modifica ore
    Route::put('/ore/{hour}', [HourController::class, 'update'])->name('hours.update');

    // Elimina ore
    Route::delete('/ore/{hour}', [HourController::class, 'destroy'])->name('hours.destroy');


    Route::get('change-password', [ChangePasswordController::class, 'index']);
    Route::post('change-password', [ChangePasswordController::class, 'store']);



    Route::post('/debug/change_permissions', static function (){
        try {
            auth()->user()->update([
                'level' => request('level')
            ]);
            return redirect('/')->with('message','livello di accesso cambiato');
        }catch (Exception $e){
            return redirect('/')->with('error',$e);
        }

    });

    /*
     *  GESTIONE ROUTE DOVE SONO
     */

    // Salva dove sono
    Route::post('/dove-siamo', [LocationController::class, 'store'])->name('locations.store');

    // Mostra tabella dove siamo
    Route::get('/dove-siamo', [LocationController::class, 'index'])->name('locations.index');

    // aggiorna posizione
    Route::put('/dove-siamo/{location}', [LocationController::class, 'update'])->name('locations.update');

    /*
     *  GESTIONE ROUTE DIPENDENTI
     */

    // Mostra tutti i dipendenti
    Route::get('/dipendenti',[UserController::class,'index'])->name('users.index');

    // Aggiorna dati dipendente
    Route::get('/dipendenti/{user}', [UserController::class, 'show'])->name('users.show');


    // Mostra pagina crea nuovo dipendente
    Route::get('/dipendenti/create', [UserController::class, 'create'])->name('users.create');

    // Salva nuovo dipendente
    Route::post('/dipendenti', [UserController::class, 'store'])->name('users.store');

    Route::delete('/dipendenti/{user}', [UserController::class, 'destroy'])->name('users.destroy');


    /*
     * GESTIONE ROUTE CHIAVI DI ACCESSO
     */

    // Mostra pagina chiavi di accesso
    Route::get('/access-keys', [AccessKeyController::class, 'index'])->name('access.index');

    // Salva nuova chiave di accesso
    Route::post('/access-keys', [AccessKeyController::class, 'store'])->name('access.store');

    // Elimina chiave di accesso
    Route::delete('/access-keys/{key}', [AccessKeyController::class, 'destroy'])->name('access.destroy');


    Route::get('/fi/{technical_report}',[TechnicalReportDetailsController::class,'show'])->name('technical_report_details.show');


    Route::get('/users/business-hours',[UserController::class,'indexBusinessHour'])->name('user.time');

});

