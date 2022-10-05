<?php

namespace App\Http\Controllers;

use App\Models\BusinessHour;
use App\Models\Order;
use App\Models\User;
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
    public function update(Request $request, Order $order): Redirector|Application|RedirectResponse
    {
        $formFields = $request->validate([
            'company' => 'required',
            'status' => 'required',
            'country' => 'required',
            'description' => 'required',
            'hourSW' => 'required',
            'progress' => 'required',
            'opening' => 'required',
            'closing' => 'required',
            'customer' => 'required',
        ]);

        $formFields['innerCode'] = $order->innerCode;
        $formFields['outerCode'] = $order->outerCode;

        $formFields['manager'] = $order->manager;

        $order->update($formFields);

        return redirect('/commesse')->with('message', 'Commessa aggiornata con successo');
    }

    // Elimina l'utente
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();
        return back()->with('message', 'Utente eliminato con successo');
    }

    // Mostra ore settimanali utente
    public function indexBusinessHour()
    {
        return view('users.business-hours.index',[
            'hours' => BusinessHour::where('user_id',auth()->id())->get()
        ]);
    }
}
