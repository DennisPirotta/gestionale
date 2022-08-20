<?php /** @noinspection ALL */

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Models\Hour;
use App\Models\HourType;
use App\Models\User;
use Carbon\Carbon;
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
        $users = User::select('id', 'name')->get();

        foreach (Holiday::with(['user','hour'])->get() as $holiday) {
            $editable = false;
            $user = $holiday->user->id;
            $title = $holiday->user->name;

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
                'start' => $holiday->hour->start,
                'end' => $holiday->hour->end,
                'id' => $holiday->id,
                'user' => $user,
                'editable' => $editable,
                'color' => $color,
                'textColor' => $text,
                'borderColor' => $border,
                'allDay' => $holiday->allDay,
            ];
        }

        return view('holidays.index', [
            'holidays' => $events,
            'left_hours' => Holiday::getLeftHours()
        ]);
    }

    public function store(Request $request)
    {
        if (!Holiday::isValid($request)) {
            return redirect('/ferie')->with('error', 'Disponibilità di ferie insufficente');
        }

        $start = new DateTime($request->start);
        $end = new DateTime($request->end);

        $hour = Hour::create([
            'start' => $start->format('Y-m-d H:i:s'),
            'end' => $end->format('Y-m-d H:i:s'),
            'user_id' => auth()->id(),
            'hour_type_id' => 6,
        ]);



        Holiday::create([
            'allDay' => true,
            'user_id' => auth()->id(),
            'hour_id' => $hour->id
        ]);

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
        if (!Holiday::isValid($request)) {
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

        $holiday->hour->update([
            'start' => $start,
            'end' => $end
        ]);

        $used = Carbon::parse($start)->diffInBusinessHours($end);
        if ($holiday->allDay){
            $used = Carbon::parse($start->setTime(0,0,0))->diffInBusinessHours($end->setTime(0,0,0));
        }

        return response(
            json_encode([
                'message' => 'ferie aggiornate con successo, Inizio: <b>' . $start->format('Y-m-d') . '</b> Fine: <b>' . $end->format('Y-m-d') . '</b> Ore utilizzate: <b>' . $used . '</b>',
                'perc' => Holiday::getLeftHours() * 100 / 160,
                'left' => Holiday::getLeftHours()
            ]),
            200
        );

    }

    public function destroy(Holiday $holiday)
    {
        if ($holiday->user->id === auth()->id()) {
            $holiday->delete();
            return back()->with('message', 'Ferie eliminate con successo');
        }
        return back()->with('error', 'Puoi modificare solo le tue ferie');
    }

}
