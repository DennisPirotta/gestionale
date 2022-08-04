<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index(){
        return view('holidays.index',[
            'holidays' => Holiday::all()
        ]);
    }
    // public function show(Customer $customer){}
    public function create(){
        return view('holidays.create');
    }
    public function store(Request $request){
        $formFields = $request->validate([
            'name' => 'required'
        ]);
        Customer::create($formFields);
        return redirect('/clienti')->with('message','Cliente inserito con successo');
    }
    public function edit(Customer $customer){
        return view('customers.edit', [
            'customer' => $customer
        ]);
    }
    public function update(Request $request, Customer $customer){
        $formFields = $request->validate([
            'name' => 'required'
        ]);
        $customer->update($formFields);
        return redirect('/clienti')->with('message','Cliente aggiornato con successo');
    }
    public function destroy(Customer $customer){
        $customer->delete();
        return back()->with('message','Cliente eliminato con successo');
    }

}
