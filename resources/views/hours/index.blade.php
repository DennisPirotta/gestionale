@extends('layouts.app')
@vite('resources/css/tailwind.css')
<style>
    @media print {
        @page {
            size: landscape;
            margin: 0
        }

        nav {
            display: none;
        }

        table {
            transform: translate(-26%, -19%) scale(0.5);
        }

        .no-print {
            display: none !important;
        }
        #main{
            padding-left: 10px !important;
            padding-right: 10px !important;
        }
    }
</style>
@section('content')
    <div id="main" class="container-fluid px-5 mt-5">
        <div class="d-flex align-items-center">
            <div class="h1 m-0">
                Ore
                {{ request('user') !== null ? App\Models\User::find(request('user'))->name : auth()->user()->name }}
                -
                {{ request('month') !== null ? Carbon\Carbon::parse(request('month'))->translatedFormat('F Y') : __('Select a month') }}
            </div>
            <div class="no-print flex ml-auto">
                <button class="btn btn-primary me-2 ms-auto"
                        onclick="window.location.href = '{{ route('expense_report.index') }}?month={{ request('month',\Carbon\Carbon::now()->format('Y-m')) }}&user={{ request('user',auth()->id()) }}'"><i
                            class="bi bi-hourglass-split me-2"></i>Nota spese
                </button>
                <button class="btn btn-primary me-2" data-bs-target="#myModal"
                        onclick="window.location.href = '{{route('hours.create')}}'" data-bs-toggle="modal"><i
                            class="bi bi-plus-circle me-2"></i>Aggiungi ore
                </button>
                <form class="m-0 d-flex" id="queryData">
                    <div class="pe-0">
                        <label for="date" class="d-none"></label><input type="month" class="form-control" name="month"
                                                                        id="date"
                                                                        value="{{ request('month') !== null ? Carbon\Carbon::parse(request('month'))->format('Y-m') : ''}}">
                    </div>

                    @role('admin|boss')
                    <div class="ps-2 me-2">
                        <label for="user" class="d-none"></label><select name="user" class="form-select" id="user">
                            <option disabled selected>Utente</option>
                            @foreach($users as $select_user)
                                <option value="{{ $select_user->id }}"
                                        @if(request('user') === (string)$select_user->id) selected @endif>{{ $select_user->name }} {{ $select_user->surname }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-primary me-2 ms-auto" onclick="window.print()"><i
                                class="bi bi-printer me-2"></i>Stampa
                    </button>
                    @else
                        <input type="hidden" name="user" value="{{ auth()->id() }}">
                    @endrole
                </form>
            </div>
        </div>
        <hr class="hr my-3">
        @php($user = App\Models\User::find(request('user')) ?? auth()->user())
        @unless($data->count() === 0 || !request('month'))
            <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-500  text-center">
                <thead class="text-xs text-gray-700 uppercase bg-gray-200 ">
                    <tr>
                        <th scope="col" class="py-2 px-4">
                            #
                        </th>
                        @foreach($period as $day)
                            <th scope="col" class="py-2 px-4 border-l border-gray-200 ">{{ $day->translatedFormat('D j') }}</th>
                        @endforeach
                        <th scope="col" class="py-2 px-4 border-l border-gray-200 ">TOT</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($data as $desc=>$type)
                    <tr class="bg-gray-50">
                        <td class="py-2 px-4 bg-gray-50 border-l">{{ $desc }}</td>
                        <td colspan="{{$period->count() + 1}}" class="bg-gray-50 py-2 px-4"></td>
                    </tr>
                    @foreach($type as $key=>$content)
                        @foreach($content as $job_type=>$hours)
                            <tr class="bg-gray-100 border-b">
                                <th scope="row" class="border-r p-1.5 grid grid-cols-1 place-items-center">
                                    <div class="font-bold">
                                        {{ $key !== 0 ? $key : '' }}
                                    </div>
                                    @if(App\Models\Order::where('innerCode',$key)->exists())
                                        <div>
                                            {{ App\Models\Order::where('innerCode',$key)->value('outerCode') }}
                                        </div>
                                        <div>
                                            {{ App\Models\Order::where('innerCode',$key)->first()->customer->name }}
                                        </div>
                                    @elseif(App\Models\TechnicalReport::where('number',(string) $key)->exists())
                                        <div>
                                            {{ App\Models\TechnicalReport::where('number',(string) $key)->first()->customer->name }}
                                        </div>
                                    @endif
                                    <div class="w-15 bg-blue-300 text-blue-800 text-xs font-medium px-2 py-0.5 rounded">
                                        {{ $job_type }}
                                    </div>
                                </th>
                                @php($count = 0)
                                @foreach($period as $day)
                                    @php($insert=true)
                                    @foreach($hours as $hour)
                                        @if($hour->date === $day->format('Y-m-d'))
                                            @php($insert=false)
                                            @php($count+=$hour->count)
                                            <td class="border-r @if($day->isWeekend() || $day->isHoliday()) bg-opacity-25 bg-primary @endif "
                                                data-datetime="{{ $day->format('Y-m-d') }}"
                                                data-row="{{ $hour_types->where('description',$desc)->value('id') }}"
                                                data-extra="{{ $key }}"
                                                data-job="{{ $job_types->where('title',$job_type)->value('id') }}"
                                                contenteditable="true"
                                                data-hour="{{ $hour->id }}">
                                                {{ $hour->count }}
                                                @if($hour_types->where('description',$desc)->value('id') == 2)
                                                    @include('hours.partial.change-fi-night')
                                                @endif
                                            </td>
                                        @endif
                                    @endforeach
                                    @if($insert)
                                        <td class="border-r @if($day->isWeekend() || $day->isHoliday()) bg-opacity-25 bg-primary @endif "
                                            data-datetime="{{ $day->format('Y-m-d') }}"
                                            data-row="{{ $hour_types->where('description',$desc)->value('id') }}"
                                            data-extra="{{ $key }}"
                                            data-job="{{ $job_types->where('title',$job_type)->value('id') }}"
                                            contenteditable="true">
                                        </td>
                                    @endif
                                @endforeach
                                <td class="border-r @if($day->isWeekend() || $day->isHoliday()) bg-opacity-25 bg-primary @endif ">{{ $count }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                @endforeach
                </tbody>
                <tfoot>
                <tr class="bg-gray-50">
                    <td class="py-2 px-4 border-l">Parziale</td>
                    <td colspan="{{$period->count()}}" class="py-2 px-4 "></td>
                </tr>
                    <tr class="bg-gray-100 border-b">
                        <th scope="row">
                            Totale
                        </th>
                        @foreach($period as $day)
                            <td class="border-r @if($day->isWeekend() || $day->isHoliday()) bg-opacity-25 bg-primary @endif ">{{ $user->hourDetails(Carbon\CarbonPeriod::create($day,$day))['total'] }}</td>
                        @endforeach
                        <td class="border-r @if($day->isWeekend() || $day->isHoliday()) bg-opacity-25 bg-primary @endif ">{{ $user->hourDetails($period)['total'] }}</td>
                    </tr>
                    <tr class="bg-gray-100 border-b">
                        <th scope="row">
                            Straordinari 25%
                        </th>
                        @foreach($period as $day)
                            <td class="border-r @if($day->isWeekend() || $day->isHoliday()) bg-opacity-25 bg-primary @endif ">{{ $user->hourDetails(Carbon\CarbonPeriod::create($day,$day))['str25'] }}</td>
                        @endforeach
                        <td class="border-r @if($day->isWeekend() || $day->isHoliday()) bg-opacity-25 bg-primary @endif ">{{ $user->hourDetails($period)['str25'] }}</td>
                    </tr>
                    <tr class="bg-gray-100 border-b">
                        <th scope="row">
                            Straordinari 50%
                        </th>
                        @foreach($period as $day)
                            <td class="border-r @if($day->isWeekend() || $day->isHoliday()) bg-opacity-25 bg-primary @endif ">{{ $user->hourDetails(Carbon\CarbonPeriod::create($day,$day))['str50'] }}</td>
                        @endforeach
                        <td class="border-r @if($day->isWeekend() || $day->isHoliday()) bg-opacity-25 bg-primary @endif ">{{ $user->hourDetails($period)['str50'] }}</td>
                    </tr>
                </tfoot>
            </table>
            </div>
            <div class="container-fluid p-5">
                <div class="row row-cols-3 g-3 justify-content-center">
                    <x-report-card :title="'Totale ore'" :icon="'bi-bar-chart-fill'"
                                   :value="$user->hourDetails($period)['total']"></x-report-card>
                    <x-report-card :title="'Ferie'" :icon="'bi-cup-hot'"
                                   :value="$user->hourDetails($period)['holidays']"></x-report-card>
                    <x-report-card :title="'Notte UE'" :icon="'bi-currency-euro'"
                                   :value="$user->hourDetails($period)['eu']"></x-report-card>
                    <x-report-card :title="'Notte Extra UE'" :icon="'bi-globe2'"
                                   :value="$user->hourDetails($period)['xeu']"></x-report-card>
                    <x-report-card :title="'Straordinari 25%'" :icon="'bi-plug'"
                                   :value="$user->hourDetails($period)['str25']"></x-report-card>
                    <x-report-card :title="'Straordinari 50%'" :icon="'bi-plug'"
                                   :value="$user->hourDetails($period)['str50']"></x-report-card>
                </div>
            </div>
        @else
            <div class="container justify-content-center text-center">
                <img src="{{ asset('images/no-orders.svg') }}" alt="" class="mx-auto" style="width: 40rem">
                <div class="fs-1">Nessuna ora trovata</div>
            </div>
        @endunless
    </div>
    <div class="tooltip bs-tooltip-top" role="tooltip" id="tooltip">
        <div class="tooltip-arrow"></div>
        <div class="tooltip-inner">
            Utilizza il punto al posto della virgola
        </div>
    </div>
    <script>
        $(() => {

            $('#date').on('change', (e) => {
                $('#queryData').submit()
            })
            $('#user').on('change', (e) => {
                $('#queryData').submit()
            })
            const token = $('meta[name="csrf-token"]').attr('content')
            let cells = $('td')
            cells.on('focusout', e => {
                let hour = $(e.target).attr('data-hour') ?? null
                let text = $(e.target).text().replace(/\b(?:EU|XEU|NO)\b/gi,'').trim().replace(',','.')
                let url = '{{ route('hours.store') }}'
                let method = 'POST'
                if (hour) {
                    url = '/ore/' + hour
                    if (text === '') method = 'DELETE'
                    else method = 'PUT'
                }
                const data = {
                    '_token': token,
                    '_method': method,
                    'count': text,
                    'date': $(e.target).attr('data-datetime'),
                    'description': null,
                    'hour_type_id': $(e.target).attr('data-row'),
                    'extra': $(e.target).attr('data-extra'),
                    'job': $(e.target).attr('data-job'),
                    'hour': hour,
                    'user_id': {{ $user->id }}
                };

                fetch(url, {
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": token,
                    },
                    credentials: "same-origin",
                    body: JSON.stringify(data),
                }).then(()=>location.reload())
            })
            cells.keypress(e => {
                if (e.which < 48 || e.which > 57) {
                    if(!(e.which == 44 || e.which == 46))
                        e.preventDefault();
                }
                if (e.keyCode === 13) {
                    e.preventDefault();
                    $(e.target).blur()
                }
            })

            $('.night').click( e => {
                let hour = $(e.target).attr('data-hour')
                fetch('/ore/'+hour+'/updateNight',{
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": token,
                    },
                    credentials: "same-origin",
                    body: JSON.stringify({
                        '_token': token,
                    }),
                }).then(()=>location.reload())

            } )

        })


    </script>
@endsection
