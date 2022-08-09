<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Country;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Status;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    // Visualizza tutte le commesse
    public function index(): Factory|View|Application
    {
        $commesse = Order::latest()->get();
        if (request('customer')) {
            $commesse = Order::latest()->filter(request(['customer']))->get();
        } elseif (request('search')) {
            $commesse = Order::latest()->filter(request(['search']))->get();
        } elseif (request('company')) {
            $commesse = Order::latest()->filter(request(['company']))->get();
        }

        Session::put('require_navbar_tools', true);

        return view('orders.index', [
            'commesse' => $commesse,
            'statuses' => Status::all()
        ]);
    }

    // Filtra per commessa
    public function show(Order $order): Factory|View|Application
    {
        return view('orders.show', [
            'commessa' => $order
        ]);
    }

    // Mostra pagina per creare una nuova commessa

    public function store(Request $request): Redirector|Application|RedirectResponse
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

        $formFields['innerCode'] = (Order::latest()->first()->innerCode) + 1;
        $formFields['outerCode'] = (Order::latest()->first()->outerCode) + 1;

        $formFields['manager'] = auth()->user()->id;

        Order::create($formFields);

        return redirect('/commesse')->with('message', 'Commessa inserita con successo');
    }

    // Salva la nuova commessa

    public function create(): Factory|View|Application
    {
        return view('orders.create', [
            'customers' => Customer::all(),
            'countries' => Country::all(),
            'companies' => Company::all(),
            'statuses' => Status::all()
        ]);
    }

    // Mostra pagina per modificare una commessa

    public function edit(Order $order): Factory|View|Application
    {
        return view('orders.edit', [
            'commessa' => $order,
            'customers' => Customer::all(),
            'countries' => Country::all(),
            'companies' => Company::all(),
            'statuses' => Status::all()
        ]);
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
