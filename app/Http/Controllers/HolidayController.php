<?php /** @noinspection ALL */

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Models\Hour;
use App\Models\HourType;
use App\Models\Location;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HolidayController extends Controller
{

    public function index()
    {
        $events = [];

        foreach (Holiday::with(['user'])->get() as $holiday) {
            $editable = false;
            $user = $holiday->user->id;
            $title = $holiday->user->name . " " . $holiday->user->surname;

            if ($holiday->user->id === auth()->id()) {
                $editable = true;
            }

            $color = 'rgba(215,239,79,0.84)'; // yellow ( default )
            $text = 'black';
            $border = 'rgb(250,192,0)';
            if ($holiday->approved) {
                $color = '#497e29'; // green
                $text = 'white';
                $border = 'rgb(32,70,15)';
            }

            $events[] = [
                'title' => $title,
                'start' => $holiday->start,
                'end' => $holiday->end,
                'id' => $holiday->id,
                'user' => $user,
                'editable' => $editable,
                'color' => $color,
                'textColor' => $text,
                'borderColor' => $border,
                'allDay' => true,
            ];


        }

        usort($events,function ($date1,$date2){
            $datetime1 = strtotime($date1['start']);
            $datetime2 = strtotime($date2['start']);
            return $datetime1 - $datetime2;
        });

        return view('holidays.index', [
            'holidays' => $events,
            'left_hours' => auth()->user()->getLeftHolidays(),
            'users' => User::with('holidayList')->get()
        ]);
    }

    public function store(Request $request)
    {
        if (auth()->user()->getLeftHolidays() <= 0) {
            return redirect('/ferie')->with('error', 'Disponibilità di ferie insufficente');
        }

        $start = Carbon::parse($request->start);
        $end = Carbon::parse($request->end);
        $allDay = $request->allDay === 'true'? true: false;
        $approved = false;

        if ($end->isPast()){
            $approved = true;
        }

        $holiday = Holiday::create([
            'start' => $start,
            'end' => $end,
            'user_id' => auth()->id(),
            'approved' => $approved
        ]);
        $holiday->sendMail();

        $period = CarbonPeriod::create($start,$end->modify('-1 day'));

        foreach ($period as $date){
            $day_start = clone $date->setTime(0,1);
            $day_end = $date->setTime(23,59);

            Hour::create([
                'count' => $day_start->diffInBusinessHours($day_end),
                'user_id' => auth()->id(),
                'hour_type_id' => 6,
                'date' => $date
            ]);
            Location::create([
                'date' => $date->format('Y-m-d'),
                'description' => 'Ferie',
                'user_id' => auth()->id()
            ]);
        }

        return redirect('/ferie')->with('message', 'Ferie richieste con successo, usate <b>' . abs(Carbon::parse($request->start)->diffInBusinessHours($request->end)) . "</b> ore");
    }

    public function create()
    {
        return view('holidays.create', [
            'holidays' => Holiday::all()
        ]);
    }

    public function update(Request $request, Holiday $holiday)
    {
        if (auth()->user()->getLeftHolidays() <= 0) {
            return response(
                json_encode([
                    'message' => 'Disponibilità di ferie insufficente',
                    'perc' => Holiday::getLeftHours() * 100 / auth()->user()->holidays,
                    'left' => Holiday::getLeftHours()
                ]),
                401
            );
        }

        $start = new DateTime($request->start);
        $end = new DateTime($request->end);
        $old_start = new DateTime($request->old_start);
        $old_end = new DateTime($request->old_end);

        $holiday->update([
            'start' => $start,
            'end' => $end
        ]);

        $period = CarbonPeriod::create($start,$end->modify('-1 day'));
        $old_period = CarbonPeriod::create($old_start,$old_end->modify('-1 day'));

        foreach ($old_period as $old_day){
            Hour::where('user_id',auth()->id())->where('date',$old_day->format('Y-m-d'))->delete();
            Location::where('user_id',auth()->id())->where('date',$old_day->format('Y-m-d'))->delete();
        }

        foreach ($period as $day) {
            $day_start = clone $day->setTime(0,1);
            $day_end = $day->setTime(23,59);
            Hour::create([
                'count' => $day_start->diffInBusinessHours($day_end),
                'user_id' => auth()->id(),
                'hour_type_id' => 6,
                'date' => $day->format('Y-m-d')
            ]);
            Location::create([
                'date' => $day->format('Y-m-d'),
                'description' => 'Ferie',
                'user_id' => auth()->id()
            ]);
        }

        $used = Carbon::parse($start)->diffInBusinessHours($end);
        if ($holiday->allDay){
            $used = Carbon::parse($start->setTime(0,0,0))->diffInBusinessHours($end->setTime(0,0,0));
        }

        return response(
            json_encode([
                'message' => 'ferie aggiornate con successo, Inizio: <b>' . $start->format('Y-m-d') . '</b> Fine: <b>' . $end->format('Y-m-d') . '</b> Ore utilizzate: <b>' . $used . '</b>',
                'perc' => auth()->user()->getLeftHolidays() * 100 / 160,
                'left' => auth()->user()->getLeftHolidays()
            ]),
            200
        );

    }

    public function destroy(Holiday $holiday)
    {
        if ($holiday->user->id === auth()->id()) {

            $period = CarbonPeriod::create($holiday->start,Carbon::parse($holiday->end)->modify('-1 day'));
            foreach ($period as $date){
                Hour::where('user_id',auth()->id())->where('date',$date->format('Y-m-d'))->delete();
                foreach (Location::where('user_id',auth()->id())->get() as $location){
                    if ($location->date === $date->format('Y-m-d') && $location->description === 'Ferie'){
                        $location->delete();
                    }
                };
            }
            $holiday->delete();
            return back()->with('message', 'Ferie eliminate con successo');
        }
        return back()->with('error', 'Puoi modificare solo le tue ferie');
    }

    public function destroyMore(Request $request){


        $holidays = Holiday::with(['hour','user'])->get();
        foreach ($request->ferie as $event){
            $holiday = $holidays->find($event);
            $this->destroy($holiday);
        }
        return back()->with('message', 'Ferie eliminate con successo');
    }

    public function approve(Request $request,Holiday $holiday){
        $holiday->update([
            'approved' => true
        ]);
        return back()->with('message','Ferie modificate con successo');
    }
}
