<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Models\Hour;
use App\Models\Location;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class HolidayController extends Controller
{
    public function index()
    {
        $events = new Collection();

        foreach (Holiday::with(['user'])->get() as $holiday) {
            $events->push([
                'title' => $holiday->user->name.' '.$holiday->user->surname,
                'start' => $holiday->start,
                'end' => $holiday->end,
                'id' => $holiday->id,
                'color' => $holiday->approved ? 'rgba(73, 126, 41, 1)' : 'rgba(215,239,79,0.84)',
                'textColor' => $holiday->approved ? 'white' : 'black',
                'borderColor' => $holiday->approved ? 'rgb(32,70,15)' : 'rgb(250,192,0)',
                'allDay' => !($holiday->permission && Carbon::parse($holiday->start)->isSameDay($holiday->end)),
                'extendedProps' => [ 'user' => $holiday->user->id ]
            ]);
        }

        return view('holidays.index', [
            'events' => $events,
            'holidays' => Holiday::all(),
            'left_hours' => auth()->user()->getLeftHolidays(),
            'users' => User::with('holidayList')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $isPermission = $request->get('request_type') === 'permission';
        if ($request->get('permission_start') === null || $request->get('permission_end') === null) {
            $isPermission = false;
        }

        $continue = true; // fix bug giorno singolo !

        if ($isPermission) {
            $start = Carbon::parse($request->get('start') . ' ' . $request->get('permission_start'));
            $end = Carbon::parse($request->get('start') . ' ' . $request->get('permission_end'));
        } elseif (!$request->has('end') && ($request->get('permission_start') === null || $request->get('permission_end') === null)) {
            $start = Carbon::parse($request->get('start'));
            $end = $start->clone()->addDay();
            $continue = false;
        } else {
            $start = Carbon::parse($request->get('start'));
            $end = Carbon::parse($request->get('end'));
        }

        Holiday::create([
            'start' => $start,
            'end' => $end,
            'user_id' => auth()->id(),
            'approved' => $end->isPast(),
            'permission' => $isPermission
        ])->sendMail();

        if ($isPermission) {
            $count = Carbon::parse($start)->diffInBusinessHours($end);
            Hour::create([
                'count' => $count,
                'user_id' => auth()->id(),
                'hour_type_id' => 6,
                'date' => $start,
            ]);
            Location::create([
                'date' => $start->format('Y-m-d'),
                'description' => 'Ferie',
                'user_id' => auth()->id(),
            ]);
        } else {
            $continue ?: $end->subDay();
            foreach (CarbonPeriod::create($start, $end) as $day) {
                if ($day->isWeekday()){
                    Hour::create([
                        'count' => 8,
                        'user_id' => auth()->id(),
                        'hour_type_id' => 6,
                        'date' => $day,
                    ]);
                    Location::create([
                        'date' => $day->format('Y-m-d'),
                        'description' => 'Ferie',
                        'user_id' => auth()->id(),
                    ]);
                }
            }
        }

//        return redirect('/ferie')->with('message', 'Ferie richieste con successo, usate <b>'.abs($count).'</b> ore');
        return redirect('/ferie')->with('message', 'Ferie richieste con successo');
    }
    public function destroy(Holiday $holiday)
    {
        if ($holiday->user->id === auth()->id() || auth()->user()->hasRole('admin|boss')) {
            $period = CarbonPeriod::create($holiday->start, Carbon::parse($holiday->end)->modify('-1 day'));
            foreach ($period as $date) {
                Hour::where('user_id', auth()->id())->where('date', $date->format('Y-m-d'))->delete();
                foreach (Location::where('user_id', auth()->id())->get() as $location) {
                    if ($location->date === $date->format('Y-m-d') && $location->description === 'Ferie') {
                        $location->delete();
                    }
                }
            }
            $holiday->sendMail(null, false, true);
            $holiday->delete();

            return back()->with('message', 'Ferie eliminate con successo');
        }

        return back()->with('error', 'Puoi modificare solo le tue ferie');
    }

    public function destroyMore(Request $request)
    {
        $holidays = Holiday::with('user')->get();
        foreach ($request->ferie as $event) {
            $holiday = $holidays->find($event);
            $this->destroy($holiday);
        }

        return back()->with('message', 'Ferie eliminate con successo');
    }

    public function approve(Request $request, Holiday $holiday)
    {
        $holiday->update([
            'approved' => true,
        ]);
        $holiday->sendMail(null, true);

        return back()->with('message', 'Ferie modificate con successo');
    }
}
