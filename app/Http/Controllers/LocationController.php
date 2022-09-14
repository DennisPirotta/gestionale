<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class LocationController extends Controller
{

    public function index()
    {
        $locations = Location::with('user')->get();
        $events = [];

        foreach ($locations as $location){
            $events[] = [
              'start' => Carbon::parse($location->date),
              'end' => Carbon::parse($location->date),
              'title' => auth()->id(),
            ];
        }

        return view('locations.index',[
            'locations' => $events
        ]);
    }

    public function store(Request $request): Redirector|Application|RedirectResponse
    {
        try {
            Location::create([
                'date' => now(),
                'description' => $request->whereami,
                'user_id' => auth()->id()
            ]);
            auth()->user()->update([
                'position' => true
            ]);
            return redirect('/')->with('message','Posizione inserita con successo');
        }catch (\Exception $e ){
            return redirect('/')->with('error','Impossibile inserire la posizione');
        }

    }
}
