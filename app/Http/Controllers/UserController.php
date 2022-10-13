<?php

namespace App\Http\Controllers;

use App\Models\BusinessHour;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class UserController extends Controller
{
    // Visualizza tutti i dipendenti
    public function index(): Factory|View|\Illuminate\Contracts\Foundation\Application
    {
        return view('users.index',[
            'users' => User::with(['company'])->get()
        ]);
    }

    // Filtra per commessa
    public function show(User $user): Factory|View|\Illuminate\Contracts\Foundation\Application
    {
        return view('users.show', [
            'user' => $user->load(['company','business_hours'])
        ]);
    }

    // Mostra pagina per modificare una commessa

    public function edit(User $user)
    {
        //
    }

    // Modifica la commessa
    public function update(Request $request, User $user): void
    {
        //
    }

    // Elimina l'utente
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();
        return back()->with('message', 'Utente eliminato con successo');
    }

    // Mostra ore settimanali utente
    public function updateBusinessHour(Request $request, User $user): RedirectResponse
    {
        //dd($request, $user->business_hours);
        foreach ($user->business_hours as $business_hour){
            $business_hour->update([
                'morning_start' => $request['days'][$business_hour->week_day]['morning_start'],
                'morning_end' => $request['days'][$business_hour->week_day]['morning_end'],
                'afternoon_start' => $request['days'][$business_hour->week_day]['afternoon_start'],
                'afternoon_end' => $request['days'][$business_hour->week_day]['afternoon_end']
            ]);
        }
        Carbon::setOpeningHours(BusinessHour::getWorkingHours($user));
        return back()->with('message','Orario di lavoro modificato correttamente');
    }
    // Mostra ore settimanali utente
    public function updateHolidaysHour(Request $request, User $user): RedirectResponse
    {
        $user->holidays = $request['holidays'];
        $user->save();
        return back()->with('message','Ore di ferie modificate correttamente');
    }
}
