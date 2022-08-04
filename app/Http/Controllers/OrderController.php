<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Country;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    // Visualizza tutte le commesse
    public function index()
    {
        $commesse = Order::latest()->get();
        if (request('customer'))    $commesse = Order::latest()->filter(request(['customer']))->get();
        elseif (request('search'))  $commesse = Order::latest()->filter(request(['search']))->get();
        elseif (request('company')) $commesse = Order::latest()->filter(request(['company']))->get();

        Session::put('require_navbar_tools',true);

        return view('orders.index',[
            'commesse' => $commesse,
            'statuses' => Status::all()
        ]);
    }
    // Filtra per commessa
    public function show(Order $order){
        return view('orders.show',[
            'commessa' => $order
        ]);
    }
    // Mostra pagina per creare una nuova commessa
    public function create(){
        return view('orders.create',[
            'customers' => Customer::all(),
            'countries' => Country::all(),
            'companies' => Company::all(),
            'statuses'  => Status::all()
        ]);
    }
    // Salva la nuova commessa
    public function store(Request $request)
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

        $formFields['innerCode'] = (Order::all()->last()->innerCode)+1;
        $formFields['outerCode'] = (Order::all()->last()->outerCode)+1;

        $formFields['manager'] = auth()->user()->id;

        Order::create($formFields);

        return redirect('/commesse')->with('message','Commessa inserita con successo');
    }
    // Mostra pagina per modificare una commessa
    public function edit(Order $order){
        return view('orders.edit',[
            'commessa' => $order,
            'customers' => Customer::all(),
            'countries' => Country::all(),
            'companies' => Company::all(),
            'statuses'  => Status::all()
        ]);
    }
    // Modifica la commessa
    public function update(Request $request, Order $order){
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

        return redirect('/commesse')->with('message','Commessa aggiornata con successo');
    }
    // Elimina la commessa
    public function destroy(Order $order){
        $order->delete();
        return back()->with('message','Commessa eliminata con successo');
    }
}
