<?php

namespace App\Http\Controllers;

use App\Models\Location;
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
        $locations = Location::with('user')->get();
        $events = [];

        foreach ($locations as $location) {
            $color = '#6C757DFF';
            if ($location->user->id === auth()->id()) {
                $color = '#0D6EFDFF';
            }
            $events[] = [
                'start' => Carbon::parse($location->date),
                'end' => Carbon::parse($location->date),
                'title' => substr($location->user->name, 0, 1).'. '.substr($location->user->surname, 0, 1).'. - '.$location->description,
                'allDay' => true,
                'description' => $location->description,
                'name' => $location->user->name,
                'surname' => $location->user->surname,
                'locationId' => $location->id,
                'backgroundColor' => $color,
            ];
        }

        return view('locations.index', [
            'locations' => $events,
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
}
