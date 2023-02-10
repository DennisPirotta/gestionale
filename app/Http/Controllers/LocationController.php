<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\LocationToggle;
use Barryvdh\Debugbar\Facades\Debugbar;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class LocationController extends Controller
{
    public function index(): Factory|View|Application
    {
        $events = [];
        foreach (Location::with('user')->filter(request(['user','sph']))->get() as $location) {
            $events[] = [
                'id' => $location->id,
                'start' => Carbon::parse($location->date),
                'end' => Carbon::parse($location->date),
                'title' => substr($location->user->name, 0, 1).'. '.substr($location->user->surname, 0, 1).'. - '.$location->description,
                'allDay' => true,
                'description' => $location->description,
                'name' => $location->user->name,
                'surname' => $location->user->surname,
                'locationId' => $location->id,
                'backgroundColor' => $location->user->id === auth()->id() ? '#0D6EFDFF' : '#6C757DFF',
                'extendedProps' => [ 'sph' => $location->sph_office ]
            ];
        }



        return view('locations.index', [
            'locations' => $events,
            'toggles' => Location::getSphOfficeData()
        ]);
    }

    public function store(Request $request): Redirector|Application|RedirectResponse
    {
        foreach (Location::with('user')->get() as $location) {
            if ($request->date === $location->date && $location->user->id === auth()->id()) {
                return back()->with('error', 'Hai gia inserito dove ti trovi in questa data');
            }
        }
        try {
            Location::create([
                'date' => $request->date,
                'description' => $request->whereami,
                'user_id' => auth()->id(),
                'sph_office' => $request->has('sph')
            ]);
            auth()->user()->update([
                'position' => true,
            ]);
            session()->forget('whereami');

            return back()->with('message', 'Posizione inserita con successo');
        } catch (Exception) {
            return back()->with('error', 'Impossibile inserire la posizione ');
        }
    }

    public function update(Request $request, Location $location): RedirectResponse
    {
        if ($location->user->id === auth()->id()) {
            $location->update([
                'description' => $request->whereami,
            ]);

            return back()->with('message', 'Posizione modificata con successo');
        }

        return back()->with('error', 'Puoi modificare solo la tua posizione');
    }

    public function destroy(Location $location)
    {
        if ($location->user->id !== auth()->id() && ! auth()->user()->hasRole('admin|boss')) {
            return back()->with('message', 'Impossibile eliminare la posizione');
        }
        $location->delete();

        return back()->with('message', 'Posizione eliminata con successo');
    }
}
