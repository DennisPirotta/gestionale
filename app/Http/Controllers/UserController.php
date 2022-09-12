<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Visualizza tutti i dipendenti
    public function index()
    {
        return view('users.index',[
            'users' => User::with(['company'])->get()
        ]);
    }

    // Filtra per commessa
    public function show(User $user)
    {
        return view('users.show', [
            'user' => $user
        ]);
    }

    // Mostra pagina per modificare una commessa

    public function edit(User $user): Factory|View|Application
    {
        return view('users.edit');
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

    // Elimina la commessa
    public function destroy(Order $order): RedirectResponse
    {
        $order->delete();
        return back()->with('message', 'Commessa eliminata con successo');
    }
}
