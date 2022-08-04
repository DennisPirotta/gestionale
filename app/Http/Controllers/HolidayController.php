<?php /** @noinspection ALL */

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
    // public function show(Holiday $holiday){}

    public function create(){
        return view('holidays.create', [
            'holidays' => Holiday::all()
        ]);
    }

    public function store(Request $request){

        $formFields = $request->validate([
            'start' => 'required',
            'end' => 'required',
        ]);

        $formFields['user'] = auth()->user()->id;

        $dayCounter = auth()->user()->holidays;

        foreach (Holiday::where('user',auth()->user()->id)->get() as $day)
            $dayCounter -= abs((strtotime($day->end) - strtotime($day->start)) / 86400);

        $remaning = $dayCounter;

        $dayCounter -= abs((strtotime($formFields['end']) - strtotime($formFields['start'])) / 86400);

        if ($dayCounter >= 0) Holiday::create($formFields);
        else return redirect("/ferie")->with('error', 'Disponibilità di ferie insufficente, disponi di '.$remaning.' giorni ');

        return redirect('/ferie')->with('message','Ferie richiesta con successo');
    }
    public function edit(Customer $customer){
        return view('customers.edit', [
            'customer' => $customer
        ]);
    }

    public function update(Request $request, Holiday $holiday){

        /*
        $data = json_decode($request);


        $formFields['user'] = auth()->user()->id;

        $dayCounter = auth()->user()->holidays;

        foreach (Holiday::where('user',auth()->user()->id)->get() as $day)
            $dayCounter -= abs((strtotime($day->end) - strtotime($day->start)) / 86400);

        $remaning = $dayCounter;

        $dayCounter -= abs((strtotime($formFields['end']) - strtotime($formFields['start'])) / 86400);

        if ($dayCounter >= 0) Holiday::create($formFields);
        else return redirect("/ferie")->with('error', 'Disponibilità di ferie insufficente, disponi di '.$remaning.' giorni ');
        */
        return redirect('/ferie')->with('message','Ferie aggiornata con successo');
    }
    public function destroy(Customer $customer){
        $customer->delete();
        return back()->with('message','Cliente eliminato con successo');
    }

}
