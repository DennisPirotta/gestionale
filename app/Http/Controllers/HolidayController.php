<?php /** @noinspection ALL */

namespace App\Http\Controllers;

use DatePeriod;
use DateInterval;
use App\Models\Customer;
use App\Models\Holiday;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;

class HolidayController extends Controller
{

    public function index()
    {
        $events = [];
        $users = User::select('id', 'name')->get();

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

        return view('holidays.index', [
            'holidays' => $events
        ]);
    }

    public function store(Request $request)
    {

        $data = $request->validate([
            'start' => 'required',
            'end' => 'required',
        ]);

        $data['user'] = auth()->user()->id;

        Holiday::create($data);

        return redirect('/ferie')->with('message', 'Ferie richieste con successo');
    }

    public function create()
    {
        return view('holidays.create', [
            'holidays' => Holiday::all()
        ]);
    }

    public function update(Request $request, Holiday $holiday)
    {

        $holiday->update([
            'start' => new DateTime($request->start),
            'end' => new DateTime($request->end)
        ]);

        return response(
            json_encode([
                'message' => 'ferie aggiornate con successo, Inizio: <b>' . $request->start . '</b> Fine: <b>' . $request->end . '</b> Giorni utilizzati: <b>?' . '</b>',
                'perc' => 0,
                'left' => 0
            ]),
            200
        );

    }

    public function destroy(Holiday $holiday)
    {
        if ($holiday->user === auth()->user()->id){
            $holiday->delete();
            return back()->with('message', 'Ferie eliminate con successo');
        }else{
            return back()->with('error', 'Puoi modificare solo le tue ferie');
        }


    }

}
