@extends('layouts.app')
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
        $other = $user->hours->whereNotIn('hour_type_id',[1,2,6]);
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
            <button class="btn btn-primary me-2 ms-auto" data-bs-target="#myModal" data-bs-toggle="modal"><i
                        class="bi bi-plus-circle me-2"></i>Aggiungi ore
            </button>
            <form class="m-0" id="queryData">
                <div class="row">
                    <div class="col pe-0">
                        <label for="date" class="d-none"></label><input type="month" class="form-control" name="mese"
                                                                        id="date" value="{{ $mese->format('Y-m') }}">
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
                        <th scope="row">{{ $order->innerCode }}<br><small class="fw-light">{{ $order->outerCode }}</small><br><small class="fw-light">{{ $order->description }}</small></th>
                        @foreach($period as $day)
                            @php($flag = true)
                            @foreach($order->order_details as $details)
                                @if($details->hour->date === $day->format('Y-m-d'))
                                    <td @if($day->isWeekend()) class="bg-secondary" @endif >{{ $details->hour->count }}<br><span class="badge text-bg-{{ $details->job_type->color }}">{{ $details->job_type->title }}</span></td>
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
                                    <td @if(Carbon::parse($day)->isWeekend()) class="bg-secondary bg-opacity-10" @endif >{{ $holiday_hour->count }}</td>
                                @endif
                            @endforeach
                            @if($flag)
                                <td @if(Carbon::parse($day)->isWeekend()) class="bg-secondary bg-opacity-10" @endif ></td>
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

    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="label">Inserimento Ore</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="/ore" class="row">
                        @csrf
                        {{-- Quantita --}}
                        <div class="col-12 d-flex justify-content-center mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="multiple_toggle">
                                <label class="form-check-label" for="multiple_toggle">Inserimento multiplo</label>
                            </div>
                        </div>

                        <div class="col-12 mb-3" id="day_start_col">
                            <div class="input-group col-md-4 col-sm-6">
                                <span class="input-group-text" id="time_pattern"><i
                                            class="bi bi-clipboard-data me-2"></i>Data</span>
                                <input type="date" class="form-control" name="day_start" id="day_start">
                            </div>
                            @error('day_start')
                            <p class="text-danger fs-6">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="col-6 mb-3 d-none" id="day_end_col">
                            <div class="input-group col-md-4 col-sm-6">
                                <span class="input-group-text"><i class="bi bi-clipboard-data me-2"></i>Fine</span>
                                <input type="date" class="form-control" name="day_end" id="day_end">
                            </div>
                            @error('day_end')
                            <p class="text-danger fs-6">{{$message}}</p>
                            @enderror
                        </div>

                        <div class="col-4">
                            <div class="input-group col-md-4 col-sm-6">
                                <span class="input-group-text"><i class="bi bi-clipboard-data me-2"></i>Ore</span>
                                <input type="text" class="form-control" name="count" value="8">
                            </div>
                            @error('count')
                            <p class="text-danger fs-6">{{$message}}</p>
                            @enderror
                        </div>
                        {{-- Box selezione tipo di ora --}}
                        <div class="col-8">
                            <div class="input-group">
                                <label class="input-group-text" for="hour_type_id"><i class="bi bi-building me-2"></i>Tipologia</label>
                                <select class="form-select" id="hour_type_id" name="hour_type_id">
                                    <option value='' selected>Seleziona la tipologia</option>
                                    @foreach($hour_types as $hour_type)
                                        <option value="{{$hour_type->id}}">{{$hour_type->description !== "Ferie" ? $hour_type->description : "Permesso"}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('hour_type_id')
                            <p class="text-danger fs-6">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="flex-column d-flex col-12 d-none" id="contentDetails">
                            <hr class="mx-auto w-75 mb-3">
                            @role('admin|boss')
                            <small class="text-warning mx-auto"><i class="bi bi-exclamation-triangle"></i>Sezione
                                dedicata all'inserimento dei dati</small>
                            <div class="input-group">
                                <label class="input-group-text" for="user_id"><i class="bi bi-person-badge me-2"></i>Utente</label>
                                <select class="form-select" id="user_id" name="user_id">
                                    <option value='' selected>Seleziona un dipendente</option>
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">{{ $user->name }} {{ $user->surname }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <hr class="mx-auto w-75 mb-3">
                            @endrole
                            {{-- Commesse --}}
                            <div class="details" id="content_1">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="input-group mb-3">
                                            <label class="input-group-text" for="job_type_id"><i
                                                        class="bi bi-gear me-2"></i>Tipo
                                                di lavoro</label>
                                            <select class="form-select" id="job_type" name="job_type_id">
                                                @foreach($job_types as $job_type)
                                                    <option
                                                            value="{{$job_type->id}}"
                                                            class="bg-{{$job_type->color}} bg-opacity-50">
                                                        {{$job_type->description}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('job_type_id')
                                        <p class="text-danger fs-6">{{$message}}</p>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <div class="input-group mb-3">
                                            <label class="input-group-text" for="order_id"><i
                                                        class="bi bi-building me-2"></i>Commessa</label>
                                            <select class="form-select" id="order_id" name="order_id">
                                                <option value="" selected>Seleziona una commessa</option>
                                                @foreach($original_orders as $order)
                                                    <option value="{{$order->id}}"
                                                            class="bg-{{$order->status->color}} bg-opacity-50">
                                                        ({{$order->innerCode}}) @if($order->outerCode !== null)
                                                            ({{ $order->outerCode }})
                                                        @endif
                                                        - {{$order->customer->name}}
                                                        [{{$order->status->description}}]
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('order_id')
                                        <p class="text-danger fs-6">{{$message}}</p>
                                        @enderror
                                    </div>

                                    <div class="col-12 d-none" id="job_description_box">
                                        <div class="input-group mb-3">
                                            <label class="input-group-text" for="job_description"><i
                                                        class="bi bi-info-circle me-2"></i>Descrizione</label>
                                            <input type="text" class="form-control" id="job_description"
                                                   name="job_description" value="{{ old('job_description') }}">
                                        </div>
                                        @error('job_description')
                                        <p class="text-danger fs-6">{{$message}}</p>
                                        @enderror
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="signed" id="timbrate"
                                                   value="0" checked>
                                            <label class="form-check-label" for="timbrate">
                                                Timbrate
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="signed" value="1"
                                                   id="modulo">
                                            <label class="form-check-label" for="modulo">
                                                Con Modulo
                                            </label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            {{-- Fogli intervento --}}
                            <div class="details" id="content_2">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="input-group mb-3">
                                            <label class="input-group-text" for="fi"><i
                                                        class="bi bi-clipboard me-2"></i>Tipo
                                                di F.I.</label>
                                            <select class="form-select" id="fi" name="fi_new">
                                                <option selected>Seleziona</option>
                                                <option value=0>
                                                    Nuovo
                                                </option>
                                                <option value=1>
                                                    Esistente
                                                </option>
                                            </select>
                                        </div>
                                        @error('fi')
                                        <p class="text-danger fs-6">{{$message}}</p>
                                        @enderror
                                    </div>
                                    <div class="hide" id="old_fi">
                                        <div class="col-12">
                                            <div class="input-group mb-3">
                                                <label class="input-group-text" for="number"><i
                                                            class="bi bi-building me-2"></i>Numero F.I</label>
                                                <select class="form-select" id="old_fi_number" name="fi_number">
                                                    @foreach($technical_reports as $technical_report)
                                                        <option value="{{$technical_report->id}}">
                                                            ({{$technical_report->number}})
                                                            - {{$technical_report->customer->name}}
                                                            @if(isset($technical_report->secondary_customer->name))
                                                                / {{ $technical_report->secondary_customer->name }}
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('old_fi_number')
                                            <p class="text-danger fs-6">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div id="new_fi" class="hide">
                                        <div class="col-12">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text"><i class="bi bi-list-ol me-2"></i>Numero</span>
                                                <input type="text" class="form-control" aria-label="Numero"
                                                       name="number" id="new_fi_number">
                                            </div>
                                        </div>
                                        @error('number')
                                        <p class="text-danger fs-6">{{$message}}</p>
                                        @enderror
                                        <div class="col-12">
                                            <div class="input-group mb-3">
                                                <label class="input-group-text" for="fi_order"><i
                                                            class="bi bi-building me-2"></i>Commessa</label>
                                                <select class="form-select" id="fi_order" name="fi_order_id">
                                                    <option value="">Non presente</option>
                                                    @foreach($orders as $order)
                                                        <option
                                                                value="{{$order->id}}"
                                                                class="bg-{{$order->status->color}} bg-opacity-50">
                                                            ({{$order->innerCode}})
                                                            - {{$order->customer->name}}
                                                            [{{$order->status->description}}]
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('fi_order')
                                            <p class="text-danger fs-6">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <div class="input-group mb-3">
                                                <label class="input-group-text" for="first_customer"><i
                                                            class="bi bi-person me-2"></i>Cliente</label>
                                                <select class="form-select" id="first_customer" name="customer_id"
                                                        required>
                                                    @foreach($customers as $customer)
                                                        <option value="{{$customer->id}}">
                                                            {{$customer->name}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('first_customer')
                                            <p class="text-danger fs-6">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <div class="input-group mb-3">
                                                <label class="input-group-text" for="second_customer"><i
                                                            class="bi bi-people me-2"></i>Cliente
                                                    Secondario</label>
                                                <select class="form-select" id="second_customer"
                                                        name="secondary_customer_id">
                                                    <option value="">Non presente</option>
                                                    @foreach($customers as $customer)
                                                        <option value="{{$customer->id}}">
                                                            {{$customer->name}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('second_customer')
                                            <p class="text-danger fs-6">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 d-flex justify-content-center mb-3">
                                        <div class="form-check mx-3">
                                            <input class="form-check-input" type="radio" name="night" id="night"
                                                   value="UE">
                                            <label class="form-check-label" for="night">
                                                Notte UE
                                            </label>
                                        </div>
                                        <div class="form-check mx-3">
                                            <input class="form-check-input" type="radio" name="night" id="night"
                                                   value="XUE">
                                            <label class="form-check-label" for="night">
                                                Notte Extra UE
                                            </label>
                                        </div>
                                        <div class="form-check mx-3">
                                            <input class="form-check-input" type="radio" name="night" id="night" checked
                                                   value="">
                                            <label class="form-check-label" for="night">
                                                Nessuna Notte
                                            </label>
                                        </div>
                                        @error('night')
                                        <p class="text-danger fs-6">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                            {{-- Assistenza --}}
                            <div class="details" id="content_3">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="input-group mb-3">
                                            <label class="input-group-text" for="help_customer"><i
                                                        class="bi bi-person me-2"></i>Cliente</label>
                                            <select class="form-select" id="help_customer" name="help_customer"
                                                    required>
                                                @foreach($customers as $customer)
                                                    <option value="{{$customer->id}}">
                                                        {{$customer->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('first_customer')
                                        <p class="text-danger fs-6">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            {{-- Input Descrizione --}}
                            <div class="details" id="content_8">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-lg-12">
                                        <div class="input-group mb-3">
                                            <label class="input-group-text" for="description"><i
                                                        class="bi bi-building me-2"></i>Descrizione</label>
                                            <input type="text" class="form-control" name="description" id="description">
                                        </div>
                                        @error('description')
                                        <p class="text-danger fs-6">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="details" id="content_10">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-lg-12">
                                        <div class="input-group mb-3">
                                            <label class="input-group-text" for="description"><i
                                                        class="bi bi-building me-2"></i>Descrizione</label>
                                            <input type="text" class="form-control" name="description" id="description">
                                        </div>
                                        @error('description')
                                        <p class="text-danger fs-6">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 justify-content-center d-flex">
                                <button class="btn btn-primary w-50" type="submit">Salva</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(() => {
            $('#fi').on('change', (event) => {
                let selected = $(event.target).val()
                $(`#new_fi_number`).prop('required', false)
                if (selected === '0') {
                    $('#new_fi_number').prop('required', true)
                    $('#new_fi').removeClass('hide')
                    $('#old_fi').addClass('hide')
                } else if (selected === '1') {
                    $('#new_fi').addClass('hide')
                    $('#old_fi').removeClass('hide')
                } else {
                    $('#new_fi').addClass('hide')
                    $('#old_fi').addClass('hide')
                }
            })

            $('#contentDetails').addClass("d-none")

            $('#job_type').on('change', function () {
                let el = $('#job_description_box');
                let val = $('#job_type').find(':selected').val()
                el.addClass("d-none")
                if (val == 5 || val == 7) el.removeClass("d-none")
            });

            $('#hour_type_id').on('change', function () {
                $('#new_fi_number').prop('required', false)
                switch ($('#hour_type_id').find(':selected').val()) {
                    case '': {
                        $('#contentDetails').addClass("d-none")
                        break
                    }
                    @foreach($hour_types as $hour_type)
                    case "{{$hour_type->id}}": {
                        $('#contentDetails').removeClass("d-none")
                        $('.details').addClass('d-none')
                        $('#content_{{$hour_type->id}}').removeClass("d-none")
                        break
                    }
                        @endforeach
                }
            });

            $('#multiple_toggle').on('change', e => {
                $('#day_end_col').toggleClass('d-none')
                $('#day_start_col').toggleClass('col-6 col-12')
                if ($(e.target).is(':checked')) $('#time_pattern').text('Inizio')
                else $('#time_pattern').text('Data')
            })

        })
    </script>

    <script>
        $(() => {
            $('#date').on('change', (e) => {
                console.log($(e.target).val())
                $('#queryData').submit()
            })
        })
    </script>
@endsection