<?php /** @noinspection ALL */

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Models\Hour;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Log;

class HolidayController extends Controller
{

    public function index()
    {
        $events = [];
        $users = User::select('id', 'name')->get();
        $hours = Hour::all();

        foreach (Holiday::all() as $holiday) {
            $editable = false;
            $user = $users->where('id', $holiday->user)->value('id');
            $title = $users->where('id', $holiday->user)->value('name');

            if ($holiday->user === auth()->user()->id) {
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

            $start = new DateTime($hours->where('holiday',$holiday->id)->value('start'));
            $end = new DateTime($hours->where('holiday',$holiday->id)->value('end'));

            Log::channel('dev')->info("q-start " . $hours->where('holiday',$holiday->id)->value('start'));
            Log::channel('dev')->info("q-end " . $hours->where('holiday',$holiday->id)->value('end'));
            Log::channel('dev')->info("var-start " . $start->format('Y-m-d'));
            Log::channel('dev')->info("var-end " . $end->format('Y-m-d'));

            $events[] = [
                'title' => $title,
                'start' => $start->modify('+1 day')->format('Y-m-d'),
                'end' => $end->format('Y-m-d'),
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

        $data = $request->validate([
            'start' => 'required',
            'end' => 'required',
        ]);

        $holiday = Holiday::create([
            'user' => auth()->user()->id,
            'allDay' => true
        ]);

        $data['holiday'] = $holiday->id;
        Hour::create($data);

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
                    'perc' => Holiday::getLeftHours() * 100 / 160,
                    'left' => Holiday::getLeftHours()
                ]),
                401
            );
        }

        Hour::where('holiday',$holiday->id)->update([
            'start' => new DateTime($request->start),
            'end' => new DateTime($request->end)
        ]);

        return response(
            json_encode([
                'message' => 'ferie aggiornate con successo, Inizio: <b>' . explode('T', $request->start)[0] . '</b> Fine: <b>' . explode('T', $request->end)[0] . '</b> Ore utilizzate: <b>' . Carbon::parse($request->start)->diffInBusinessHours($request->end) . '</b>',
                'perc' => Holiday::getLeftHours() * 100 / 160,
                'left' => Holiday::getLeftHours()
            ]),
            200
        );

    }

    public function destroy(Holiday $holiday)
    {
        if ($holiday->user === auth()->user()->id) {
            $holiday->delete();
            Hour::where('id',$holiday->id)->delete();
            return back()->with('message', 'Ferie eliminate con successo');
        }
        return back()->with('error', 'Puoi modificare solo le tue ferie');
    }

}
