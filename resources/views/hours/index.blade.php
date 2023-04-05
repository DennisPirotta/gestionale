@extends('layouts.app')
@vite('resources/css/tailwind.css')
<style>
    th, td{
        max-width: 15px !important;
    }
    th[scope=row]{
        overflow-wrap: break-word;
    }
    @media print {
        @page {
            size: landscape;
            margin: 0
        }

        nav {
            display: none;
        }

        .no-print {
            display: none !important;
        }
    }
</style>
@section('content')
    <div id="main" class="container-fluid px-5 mt-5">
        <div class="d-flex align-items-center">
            <div class="h1 m-0">
                Ore
                {{ request('user') !== null ? App\Models\User::find(request('user'))->name . ' ' . App\Models\User::find(request('user'))->surname : auth()->user()->name . ' ' . auth()->user()->surname }}
                -
                {{ request('month') !== null ? Carbon\Carbon::parse(request('month'))->translatedFormat('F Y') : __('Select a month') }}
            </div>
            <div class="no-print flex ml-auto">
                <button class="btn btn-primary me-2 ms-auto"
                        onclick="window.location.href = '{{ route('expense_report.index') }}?month={{ request('month',\Carbon\Carbon::now()->format('Y-m')) }}&user={{ request('user',auth()->id()) }}'"><i
                        class="bi bi-hourglass-split me-2"></i>Nota spese
                </button>
                <button class="btn btn-primary me-2" data-bs-target="#myModal"
                        onclick="sessionStorage.setItem('user',{{ request('user',auth()->id()) }}); window.location.href = '{{  route('hours.create') }}'" data-bs-toggle="modal"><i
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
                            @foreach(App\Models\User::orderBy('surname')->get() as $user)
                                <option value="{{ $user->id }}" @if(request('user') === (string)$user->id) selected @endif>
                                    {{ $user->surname }} {{ $user->name }}
                                </option>
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
    </div>
    @php($user = App\Models\User::find(request('user')) ?? auth()->user())
    <div class="max-w-10xl mx-auto sm:px-6 lg:px-8">
        <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
            @if($order_hours->count() === 0 && $technical_report_hours->count() === 0 && $other_hours->count() === 0)
                <h1 class="p-6 text-gray-900">Nessuna ora disponibile</h1>
            @else
                <table class="w-full text-sm text-center text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-200">
                    <tr>
                        <th scope="col" class="py-2 px-4">
                            #
                        </th>
                        @foreach($period as $day)
                            <th scope="col" class="border-l border-gray-300 @if($day->isToday()) bg-gray-400 @endif ">{{ $day->translatedFormat('D') }}<br>{{ $day->translatedFormat('j') }}</th>
                        @endforeach
                        <th scope="col">Tot</th>
                    </tr>
                    </thead>
                    <tbody>
                    @include('hours.partial.order-table-section')
                    @include('hours.partial.technical-report-table-section')
                    @include('hours.partial.other-hours-table-section')
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
                            <td class="border-r @if($day->isWeekend() || $day->isHoliday()) bg-opacity-25 bg-primary @endif ">
                                {{ $user->hoursInDay($day)['total'] }}
                            </td>
                        @endforeach
                        <td class="border-r @if($day->isWeekend() || $day->isHoliday()) bg-opacity-25 bg-primary @endif ">{{ $user->hourDetails($period)['total'] }}</td>
                    </tr>
                    <tr class="bg-gray-100 border-b">
                        <th scope="row">
                            Straordinari 25%
                        </th>
                        @foreach($period as $day)
                            <td class="border-r @if($day->isWeekend() || $day->isHoliday()) bg-opacity-25 bg-primary @endif @if($user->hoursInDay($day)['str25'] < 0) bg-red-200 text-red-900 @endif">
                                {{ $user->hoursInDay($day)['str25'] }}
                            </td>
                        @endforeach
                        <td class="border-r @if($day->isWeekend() || $day->isHoliday()) bg-opacity-25 bg-primary @endif @if($user->hourDetails($period)['str25'] < 0) bg-red-200 text-red-900 @endif">{{ $user->hourDetails($period)['str25'] }}</td>
                    </tr>
                    <tr class="bg-gray-100 border-b">
                        <th scope="row">
                            Straordinari 50%
                        </th>
                        @foreach($period as $day)
                            <td class="border-r @if($day->isWeekend() || $day->isHoliday()) bg-opacity-25 bg-primary @endif ">{{ $user->hoursInDay($day)['str50'] }}</td>
                        @endforeach
                        <td class="border-r @if($day->isWeekend() || $day->isHoliday()) bg-opacity-25 bg-primary @endif ">{{ $user->hourDetails($period)['str50'] }}</td>
                    </tr>
                    </tfoot>
                </table>
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
            @endif
        </div>
    </div>
    <script>
        $(()=>{
            window.onbeforeunload = () => localStorage.setItem('scroll', window.scrollY)
            $(()=>{

                const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
                const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))

                $('#date').on('change', (e) => {
                    $('#queryData').submit()
                })
                $('#user').on('change', (e) => {
                    $('#queryData').submit()
                })
                window.scrollTo(0,localStorage.getItem('scroll') ?? 0)
                $(document).click((e)=>{
                    $('div[data-popover]').not($('#'+$(e.target).attr('data-popover-target'))).each((i,e)=>{
                        $(e).addClass('invisible opacity-0')
                        $(e).removeClass('visible opacity-100')
                    })
                })
            })
        })

        let target

        $('div[contenteditable="true"]').focus((e)=>{
            target = $(e.target).clone().children().remove().end().text().trim().replace(',','.')
        })

        $('td').focusout((e)=>{
            let count = $(e.target).clone().children().remove().end().text().trim().replace(',','.')
            let child = $(e.target).children()
            let token = $('meta[name="csrf-token"]').attr('content')
            let headers = {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": token,
            }
            if(target !== count){
                if (child.length > 0){
                    let id = child.text()
                    if(count === '0' || count === ''){
                        fetch(`ore/${id}`,{
                            method: 'POST',
                            headers: headers,
                            credentials: "same-origin",
                            body: JSON.stringify({
                                '_method': 'DELETE',
                                '_token': token
                            })
                        }).then(()=>location.reload())
                    }else{
                        fetch(`ore/${id}`,{
                            method: 'POST',
                            headers: headers,
                            credentials: "same-origin",
                            body: JSON.stringify({
                                'count': count,
                                '_method': 'PUT',
                                '_token': token
                            })
                        }).then(()=>location.reload())
                    }

                }else{
                    fetch('{{ route('hours.store') }}',{
                        method: 'POST',
                        headers: headers,
                        credentials: "same-origin",
                        body: JSON.stringify({
                            'count': $(e.target).text().trim().replace(',','.'),
                            'date': $(e.target).attr('data-date'),
                            'hour_type_id': $(e.target).attr('data-hour-type'),
                            '_token': token,
                            'user_id': {{ request('user') }}
                        })
                    }).then((response) => response.json())
                        .then((hour) => {
                            if (hour.hour_type_id === '2'){
                                fetch('{{ route('technical-report-hours.store') }}',{
                                    method: 'POST',
                                    headers: headers,
                                    credentials: "same-origin",
                                    body: JSON.stringify({
                                        'hour_id': hour.id,
                                        'nightEU': false,
                                        'nightExtraEU': false,
                                        'technical_report_id': $(e.target).attr('data-technical-report-id'),
                                        '_token': token
                                    })
                                }).then(()=>location.reload())
                            }else if (hour.hour_type_id === '1'){
                                console.log(hour.id)
                                console.log(JSON.stringify({
                                    'signed': true,
                                    'order_id': $(e.target).attr('data-order-id'),
                                    'hour_id': hour.id,
                                    'job_type_id': 1,
                                    '_token': token
                                }))
                                fetch('{{ route('order-hours.store') }}',{
                                    method: 'POST',
                                    headers: headers,
                                    credentials: "same-origin",
                                    body: JSON.stringify({
                                        'signed': true,
                                        'order_id': $(e.target).attr('data-order-id'),
                                        'hour_id': hour.id,
                                        'job_type_id': 1,
                                        '_token': token
                                    })
                                }).then(()=>location.reload())
                            }else{
                                location.reload()
                            }
                        });
                }
            }

        }).keypress(e => {
            if (e.which < 48 || e.which > 57) {
                if(!(e.which == 44 || e.which == 46))
                    e.preventDefault();
            }
            if (e.keyCode === 13) {
                e.preventDefault();
                $(e.target).blur()
            }
        })
    </script>
@endsection
