<?php

namespace App\Http\Controllers;

use App\Models\Hour;
use Illuminate\Http\Request;

class HourController extends Controller
{
    public function index(){
        return view('hours.index',[
            'hours' => Hour::all()
        ]);
    }
    // public function show(Customer $customer){}
    public function create(){
        return view('hours.create');
    }
    public function store(Request $request){
        $formFields = $request->validate([
            'name' => 'required'
        ]);
        Hour::create($formFields);
        return redirect('/ore')->with('message','Ore inserite con successo');
    }
    public function edit(Hour $hour){
        return view('hours.edit', [
            'hour' => $hour
        ]);
    }
    public function update(Request $request, Hour $hour){
        $formFields = $request->validate([
            'name' => 'required'
        ]);
        $hour->update($formFields);
        return redirect('/ore')->with('message','Ore aggiornate con successo');
    }
    public function destroy(Hour $hour){
        $hour->delete();
        return back()->with('message','Ore eliminate con successo');
    }
}
