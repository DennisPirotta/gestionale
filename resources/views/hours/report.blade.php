@extends('layouts.app')
<style>
    @media print {
        @page {
            size: landscape;
            margin: 0
        }

        .table {
            font-size: 10px;
        }

        .x-card-value {
            font-size: 20px !important;
            padding-left: 10px !important;
            padding-right: 10px !important;
        }

        .x-card-title {
            font-size: 10px !important;
            padding-left: 10px !important;
            padding-right: 10px !important;
        }

        .container-fluid {
            padding-left: 5px !important;
            padding-right: 5px !important;
        }

        input {
            display: none !important;
        }

        button {
            display: none !important;
        }

        select {
            display: none !important;
        }

        td {
            padding: 1px !important;
        }

        .dayName {
            display: none !important;
        }
    }
</style> <!-- Stili per stampa -->
@section('content')
    @php
        use App\Models\Order;use App\Models\TechnicalReport;use Carbon\Carbon;
        use Carbon\CarbonPeriod;
        use App\Models\User;
        use Illuminate\Support\Facades\Session;
        $period = CarbonPeriod::create(Carbon::now()->firstOfMonth(),Carbon::now()->lastOfMonth());
        $mese = Carbon::now();
        $user = User::find(request('user'));
        if ($user){
            $other = $user->hours->whereNotIn('hour_type_id',[1,2,6]);
            $user->load('business_hours');
            if (($user->id !== auth()->id()) && !auth()->user()->hasRole('admin|boss')){
                    Session::flash('error', 'Puoi vedere solo i tuoi report');
                    header('Location: /ore');
            }
        }
        if(request('mese')) {
            $mese = Carbon::parse(request('mese'));
            $period = CarbonPeriod::create(Carbon::parse(request('mese'))->firstOfMonth(),Carbon::parse(request('mese'))->lastOfMonth());
        }
    @endphp
    <div class="container-fluid px-5 mt-5 table-responsive">
        <div class="d-flex align-items-center">
            <div class="h1 m-0">Ore
                @if(request('mese') === null || request('user') === null)
                @else
                    <b>{{ $mese->translatedFormat('F Y') }}
                        - {{ $user->name }} {{ $user->surname }}</b>
                @endif
            </div>
            <button class="btn btn-primary me-2 ms-auto" onclick="window.print()"><i class="bi bi-printer me-2"></i>Stampa
            </button>
            <form class="m-0" id="queryData">
                <div class="row">
                    <div class="col pe-0">
                        <label for="date" class="d-none"></label><input type="month" class="form-control" name="mese"
                                                                        id="date" value="{{ $mese->format('Y-m') }}">
                    </div>
                    <div class="col ps-2">
                        <label for="user" class="d-none"></label><select name="user" class="form-select" id="user">
                            <option disabled selected>Utente</option>
                            @foreach($users as $select_user)
                                <option value="{{ $select_user->id }}"
                                        @if(request('user') === (string)$select_user->id) selected @endif>{{ $select_user->name }} {{ $select_user->surname }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>

        </div>
        <hr class="hr">
        @if(request('mese') === null || request('user') === null)
            <div class="container justify-content-center text-center">
                <img src="{{ asset('images/no-orders.svg') }}" alt="" style="width: 40rem">
                <div class="fs-1">Seleziona un utente e un mese</div>
            </div>
        @else
            <table class="table table-bordered text-center">
                <thead>
                <tr>
                    <th>Tipo</th>
                    @foreach($period as $day)
                        <th>
                                <span class="dayName">
                                    {{ $day->translatedFormat('D') }}
                                </span>
                            <span>
                                    {{ $day->translatedFormat('j') }}
                                </span>
                        </th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @php
                    $orders = Order::withWhereHas('order_details',static function ($query) use ($user,$period){
                            $query->withWhereHas('hour',static function ($query) use ($user,$period){
                                $query->where('user_id',$user->id)->whereBetween('date',[$period->first(), $period->last()]);
                            });
                        });
                    $technical_reports = TechnicalReport::withWhereHas('technical_report_details',static function ($query) use ($user,$period){
                            $query->withWhereHas('hour',static function ($query) use ($user,$period){
                                $query->where('user_id',$user->id)->whereBetween('date',[$period->first(), $period->last()]);
                            });
                        });
                @endphp
                @if($orders->get()->isNotEmpty())
                    <tr>
                        <th scope="row" colspan="{{ $period->count() + 1 }}" class="border-end-0 text-start">Commesse
                        </th>
                    </tr>
                @endif
                @foreach($orders->get() as $order)
                    <tr>
                        <th scope="row">{{ $order->innerCode }}</th>
                        @foreach($period as $day)
                            @php($flag = true)
                            @foreach($order->order_details as $details)
                                @if($details->hour->date === $day->format('Y-m-d'))
                                    <td @if($day->isWeekend()) class="bg-secondary" @endif >{{ $details->hour->count }}</td>
                                    @php($flag = false)
                                @endif
                            @endforeach
                            @if($flag)
                                <td @if($day->isWeekend()) class="bg-secondary bg-opacity-10" @endif ></td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
                @if($technical_reports->get()->isNotEmpty())
                    <tr>
                        <th scope="row" colspan="{{ $period->count() + 1 }}" class="border-end-0 text-start">Fogli
                            Intervento
                        </th>
                    </tr>
                @endif
                @foreach($technical_reports->get() as $technical_report)
                    <tr>
                        <th scope="row">{{ $technical_report->number }}</th>
                        @foreach($period as $day)
                            @php($flag = true)
                            @foreach($technical_report->technical_report_details as $details)
                                @if($details->hour->date === $day->format('Y-m-d'))
                                    <td @if($day->isWeekend()) class="bg-secondary" @endif >
                                        {{ $details->hour->count }}
                                        @if($details->nightEU)
                                            <span class="badge text-bg-primary">EU</span>
                                        @elseif($details->nightExtraEU)
                                            <span class="badge text-bg-success">XEU</span>
                                        @endif
                                    </td>
                                    @php($flag = false)
                                @endif
                            @endforeach
                            @if($flag)
                                <td @if($day->isWeekend()) class="bg-secondary bg-opacity-10" @endif ></td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
                @if($user->hourDetails($period)['total'] > 0)
                    <tr>
                        <th scope="row" colspan="{{ $period->count() + 1 }}" class="border-end-0 text-start">Ferie</th>
                    </tr>
                    <tr>
                        <td></td>
                        @foreach($period as $day)
                            @php($flag = true)
                            @foreach($user->hoursInPeriod($period)->filter(static function($item){ return $item->hour_type_id === 6; }) as $holiday_hour)
                                @if($holiday_hour->date === $day->format('Y-m-d'))
                                    @php($flag = false)
                                    <td @if($day->isWeekend()) class="bg-secondary bg-opacity-10" @endif >{{ $holiday_hour->count }}</td>
                                @endif
                            @endforeach
                            @if($flag)
                                <td @if($day->isWeekend()) class="bg-secondary bg-opacity-10" @endif ></td>
                            @endif
                        @endforeach
                    </tr>
                @endif
                @unless($other->count() < 0)
                    @if($other->where('hour_type_id',8)->count() > 0)
                        <tr>
                            <th scope="row" colspan="{{ $period->count() + 1 }}" class="border-end-0 text-start">
                                Ufficio
                            </th>
                        </tr>
                        <tr>
                            <td></td>
                            @foreach($period as $day)
                                @php($flag = false)

                                @foreach($other->where('hour_type_id',8) as $office)
                                    @if($office->date === $day->format('Y-m-d'))
                                        <td @if($day->isWeekend()) class="bg-secondary bg-opacity-10" @endif >{{ $office->count }}</td>
                                        @php($flag = true)
                                    @endif
                                @endforeach

                                @if(!$flag)
                                    <td @if($day->isWeekend()) class="bg-secondary bg-opacity-10" @endif ></td>
                                @endif
                            @endforeach
                        </tr>
                    @endif

                    @if($other->where('hour_type_id',3)->count() > 0)
                        <tr>
                            <th scope="row" colspan="{{ $period->count() + 1 }}" class="border-end-0 text-start">
                                Assistenza
                            </th>
                        </tr>
                        <tr>
                            <td></td>
                            @foreach($period as $day)
                                @php($flag = false)

                                @foreach($other->where('hour_type_id',3) as $office)
                                    @if($office->date === $day->format('Y-m-d'))
                                        <td @if($day->isWeekend()) class="bg-secondary bg-opacity-10" @endif >{{ $office->count }}</td>
                                        @php($flag = true)
                                    @endif
                                @endforeach

                                @if(!$flag)
                                    <td @if($day->isWeekend()) class="bg-secondary bg-opacity-10" @endif ></td>
                                @endif
                            @endforeach
                        </tr>
                    @endif

                    @if($other->where('hour_type_id',4)->count() > 0)
                        <tr>
                            <th scope="row" colspan="{{ $period->count() + 1 }}" class="border-end-0 text-start">
                                AVIS
                            </th>
                        </tr>
                        <tr>
                            <td></td>
                            @foreach($period as $day)
                                @php($flag = false)

                                @foreach($other->where('hour_type_id',4) as $office)
                                    @if($office->date === $day->format('Y-m-d'))
                                        <td @if($day->isWeekend()) class="bg-secondary bg-opacity-10" @endif >{{ $office->count }}</td>
                                        @php($flag = true)
                                    @endif
                                @endforeach

                                @if(!$flag)
                                    <td @if($day->isWeekend()) class="bg-secondary bg-opacity-10" @endif ></td>
                                @endif
                            @endforeach
                        </tr>
                    @endif

                    @if($other->where('hour_type_id',5)->count() > 0)
                        <tr>
                            <th scope="row" colspan="{{ $period->count() + 1 }}" class="border-end-0 text-start">
                                Corso
                            </th>
                        </tr>
                        <tr>
                            <td></td>
                            @foreach($period as $day)
                                @php($flag = false)

                                @foreach($other->where('hour_type_id',5) as $office)
                                    @if($office->date === $day->format('Y-m-d'))
                                        <td @if($day->isWeekend()) class="bg-secondary bg-opacity-10" @endif >{{ $office->count }}</td>
                                        @php($flag = true)
                                    @endif
                                @endforeach

                                @if(!$flag)
                                    <td @if($day->isWeekend()) class="bg-secondary bg-opacity-10" @endif ></td>
                                @endif
                            @endforeach
                        </tr>
                    @endif

                    @if($other->where('hour_type_id',7)->count() > 0)
                        <tr>
                            <th scope="row" colspan="{{ $period->count() + 1 }}" class="border-end-0 text-start">
                                Malattia
                            </th>
                        </tr>
                        <tr>
                            <td></td>
                            @foreach($period as $day)
                                @php($flag = false)

                                @foreach($other->where('hour_type_id',7) as $office)
                                    @if($office->date === $day->format('Y-m-d'))
                                        <td @if($day->isWeekend()) class="bg-secondary bg-opacity-10" @endif >{{ $office->count }}</td>
                                        @php($flag = true)
                                    @endif
                                @endforeach

                                @if(!$flag)
                                    <td @if($day->isWeekend()) class="bg-secondary bg-opacity-10" @endif ></td>
                                @endif
                            @endforeach
                        </tr>
                    @endif

                    @if($other->where('hour_type_id',9)->count() > 0)
                        <tr>
                            <th scope="row" colspan="{{ $period->count() + 1 }}" class="border-end-0 text-start">
                                Visita medica
                            </th>
                        </tr>
                        <tr>
                            <td></td>
                            @foreach($period as $day)
                                @php($flag = false)

                                @foreach($other->where('hour_type_id',9) as $office)
                                    @if($office->date === $day->format('Y-m-d'))
                                        <td @if($day->isWeekend()) class="bg-secondary bg-opacity-10" @endif >{{ $office->count }}</td>
                                        @php($flag = true)
                                    @endif
                                @endforeach

                                @if(!$flag)
                                    <td @if($day->isWeekend()) class="bg-secondary bg-opacity-10" @endif ></td>
                                @endif
                            @endforeach
                        </tr>
                    @endif

                    @if($other->where('hour_type_id',10)->count() > 0)
                        <tr>
                            <th scope="row" colspan="{{ $period->count() + 1 }}" class="border-end-0 text-start">
                                Altro
                            </th>
                        </tr>
                        <tr>
                            <td></td>
                            @foreach($period as $day)
                                @php($flag = false)

                                @foreach($other->where('hour_type_id',10) as $office)
                                    @if($office->date === $day->format('Y-m-d'))
                                        <td @if($day->isWeekend()) class="bg-secondary bg-opacity-10" @endif >{{ $office->count }}</td>
                                        @php($flag = true)
                                    @endif
                                @endforeach

                                @if(!$flag)
                                    <td @if($day->isWeekend()) class="bg-secondary bg-opacity-10" @endif ></td>
                                @endif
                            @endforeach
                        </tr>
                    @endif

                @endunless
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="{{ $period->count() + 1}}" class="p-3"></td>
                </tr>
                <tr id="straordinari">
                    <th scope="row">Straordinari</th>
                    @foreach($period as $day)
                        @php($count = 0)
                        @foreach($user->hours as $hour)
                            @if($hour->date === $day->format('Y-m-d'))
                                @php($count += $hour->count)
                            @endif
                        @endforeach
                        @php($current = $user->business_hours->where('week_day',strtolower($day->format('l')))->first())
                        @if($current === null)
                            <td @if($day->isWeekend()) class="bg-secondary bg-opacity-10" @endif >{{ $count }}</td>
                        @else
                            @if($count - ( Carbon::parse($current->morning_start)->diffInBusinessHours($current->afternoon_end) ) < 0 )
                                <td @if($day->isWeekend()) class="bg-secondary bg-opacity-10" @endif >0</td>
                            @else
                                <td @if($day->isWeekend()) class="bg-secondary bg-opacity-10" @endif >{{ $count - ( Carbon::parse($current->morning_start)->diffInBusinessHours($current->afternoon_end) ) }}</td>
                            @endif
                        @endif
                    @endforeach
                </tr>
                <tr id="totale">
                    <th scope="row">Totale</th>
                    @foreach($period as $day)
                        @php($count = 0)
                        @foreach($user->hours as $hour)
                            @if($hour->date === $day->format('Y-m-d'))
                                @php($count += $hour->count)
                            @endif
                        @endforeach
                        <td @if($day->isWeekend()) class="bg-secondary bg-opacity-10" @endif >{{ $count }}</td>
                    @endforeach
                </tr>
                </tfoot>
            </table>
    </div>
    <div class="container p-5">
        <div class="row g-3 justify-content-center">
            <x-report-card :title="'Totale ore'" :icon="'bi-bar-chart-fill'"
                           :value="$user->hourDetails($period)['total']"></x-report-card>
            <x-report-card :title="'Ferie'" :icon="'bi-cup-hot'"
                           :value="$user->hourDetails($period)['holidays']"></x-report-card>
            <x-report-card :title="'Notte UE'" :icon="'bi-currency-euro'"
                           :value="$user->hourDetails($period)['eu']"></x-report-card>
            <x-report-card :title="'Notte Extra UE'" :icon="'bi-globe2'"
                           :value="$user->hourDetails($period)['xeu']"></x-report-card>
            <x-report-card :title="'Ore Festivi'" :icon="'bi-calendar4-week'"
                           :value="$user->hourDetails($period)['festive']"></x-report-card>
        </div>
    </div>
    @endif

    <script>
        $(() => {
            $('#date').on('change', () => {
                $('#queryData').submit()
            })
            $('#user').on('change', () => {
                $('#queryData').submit()
            })
        })
    </script>
@endsection