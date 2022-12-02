<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->get();

        return view('customers.index', [
            'customers' => $customers,
        ]);
    }

    // public function show(Customer $customer){}

    public function store(Request $request)
    {
        $formFields = $request->validate([
            'name' => 'required',
        ]);
        Customer::create($formFields);

        return redirect('/clienti')->with('message', 'Cliente inserito con successo');
    }

    public function create()
    {
        return view('customers.create');
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', [
            'customer' => $customer,
        ]);
    }

    public function update(Request $request, Customer $customer)
    {
        $formFields = $request->validate([
            'name' => 'required',
        ]);
        $customer->update($formFields);

        return redirect('/clienti')->with('message', 'Cliente aggiornato con successo');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return back()->with('message', 'Cliente eliminato con successo');
    }
}
