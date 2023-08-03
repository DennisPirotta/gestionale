<?php

namespace App\Http\Controllers;

use App\Helper\GraphHelper;
use App\Models\Holiday;
use App\Models\Hour;
use App\Models\User;
use App\Models\Location;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model\BodyType;
use Microsoft\Graph\Model\DateTimeTimeZone;
use Microsoft\Graph\Model\Event;
use Microsoft\Graph\Model\ItemBody;
use Microsoft\Graph\Model\Location as EventLocation;

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

        $holiday = Holiday::create([
            'start' => $start,
            'end' => $end,
            'user_id' => auth()->id(),
            'approved' => $end->isPast(),
            'permission' => $isPermission
        ]);

        $holiday->office_id = json_encode($this->add_to_office($holiday));
        $holiday->sendMail();

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
                if ($day->isWeekday()) {
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
            if ($holiday->office_id !== null) {
                $this->remove_from_office($holiday);
            }
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

    private function add_to_office(Holiday $holiday): array|null
    {
        $event = new Event();
        $event->setSubject("Ferie " . $holiday->user->name . ' ' . $holiday->user->surname);

        $event_body = new ItemBody();
        $event_body->setContentType(new BodyType(BodyType::HTML));
        $event_body->setContent("Si ricorda che oggi " . $holiday->user->name . " " . $holiday->user->surname . " è in ferie.");

        $event_start = new DateTimeTimeZone();
        $event_start->setDateTime(Carbon::parse($holiday->start)->toISOString());
        $event_start->setTimeZone("Europe/Rome");

        $event_end = new DateTimeTimeZone();
        $event_end->setDateTime(Carbon::parse($holiday->end)->toISOString());
        $event_end->setTimeZone("Europe/Rome");

        $location = new EventLocation();
        $location->setDisplayName('Ferie');

        $event->setBody($event_body);
        $event->setStart($event_start);
        $event->setEnd($event_end);
        $event->setLocation($location);

        try {
            $data = [
                "client_id" => config('services.microsoft_graph.client_id'),
                "client_secret" => config('services.microsoft_graph.client_secret'),
                "grant_type" => "client_credentials",
                "scope" => "https://graph.microsoft.com/.default"
            ];
            $response = Http::asForm()->post(
                url: "https://login.microsoftonline.com/".config('services.microsoft_graph.tenant_id')."/oauth2/v2.0/token",
                data: $data
            );
            $token = $response->json('access_token');
            $graph = new Graph();
            $graph->setAccessToken($token);

            $users = [
                'Admin SPH' => '464c0bdb-c888-4bc1-b4f4-4a7f80b31157',
                'Admin 3D' => '6bc96584-256c-40cc-ac01-1397d92e9db3',
            ];
            foreach ($users as $user => $id) {
                $res = $graph->createRequest('POST', "/users/$id/calendar/events")
                    ->attachBody($event)
                    ->execute();
                $users[$user] = $res->getBody()['id'];
            }
            return $users;
        } catch (\Exception|GuzzleException $e) {
            return null;
        }
    }

    private function remove_from_office(Holiday $holiday): void
    {
        try {
            $data = [
                "client_id" => config('services.microsoft_graph.client_id'),
                "client_secret" => config('services.microsoft_graph.client_secret'),
                "grant_type" => "client_credentials",
                "scope" => "https://graph.microsoft.com/.default"
            ];
            $response = Http::asForm()->post(
                url: "https://login.microsoftonline.com/".config('services.microsoft_graph.tenant_id')."/oauth2/v2.0/token",
                data: $data
            );
            $token = $response->json('access_token');
            $graph = new Graph();
            $graph->setAccessToken($token);

            $users = [
                'Admin SPH' => '464c0bdb-c888-4bc1-b4f4-4a7f80b31157',
                'Admin 3D' => '6bc96584-256c-40cc-ac01-1397d92e9db3',
            ];

            foreach ($users as $user => $id) {
                $id = json_decode($holiday->office_id)[$user];
                $graph->createRequest('DELETE', "/users/$id/calendar/events/$id")->execute();
            }
            return;
        } catch (\Exception|GuzzleException $e) {
            return;
        }
    }
}
