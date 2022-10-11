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
        use App\Models\HourType;use App\Models\OrderDetails;use App\Models\TechnicalReportDetails;use Carbon\Carbon;
        use Carbon\CarbonPeriod;
        use App\Models\User;
        $period = CarbonPeriod::create(Carbon::now()->firstOfMonth(),Carbon::now()->lastOfMonth());
        $mese = Carbon::now();
        if(request('mese')) {
            $mese = Carbon::parse(request('mese'));
            $period = CarbonPeriod::create(Carbon::parse(request('mese'))->firstOfMonth(),Carbon::parse(request('mese'))->lastOfMonth());
        }
    @endphp
    <div class="container-fluid px-5 my-5 table-responsive">
        <div class="d-flex align-items-center">
            <div class="h1 m-0">Report ore
                @if(request('mese') === null && request('user') === null)
                @else
                    <b>{{ $mese->translatedFormat('F Y') }}
                        - {{ User::find(request('user'))->name }} {{ User::find(request('user'))->surname }}</b>
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
                            @foreach($users as $user)
                                <option value="{{ $user->id }}"
                                        @if(request('user') === (string)$user->id) selected @endif>{{ $user->name }} {{ $user->surname }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>

        </div>
        <hr class="hr">
        @if(request('mese') === null && request('user') === null)
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
                    $user = User::find(request('user'));
                    $data = $user->hours->where(static function ($el){
                        return Carbon::parse($el->date)->format('Y-m') === request('mese');
                    })->groupBy('hour_type_id');
                @endphp
                @foreach($data as $type=>$hours)
                    <tr>
                        <th scope="row" class="text-start border-end-0">{{ HourType::find($type)->description }}</th>
                        <td colspan="{{ $period->count() }}" class="border-start-0"></td>
                    </tr>
                    <tr>
                        <th scope="row"></th>
                        @foreach($period as $day)
                            @php($flag = false)
                            @foreach($hours as $hour)
                                @if($hour->date === $day->format('Y-m-d'))
                                    <td>{{ $hour->count }}</td>
                                    @php($flag = true)
                                @endif
                            @endforeach
                            @if(!$flag)
                                <td></td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
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
                            <td>{{ $count }}</td>
                        @else
                            @if($count - ( Carbon::parse($current->morning_start)->diffInBusinessHours($current->afternoon_end) ) < 0 )
                                <td>0</td>
                            @else
                                <td>{{ $count - ( Carbon::parse($current->morning_start)->diffInBusinessHours($current->afternoon_end) ) }}</td>
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
                        <td>{{ $count }}</td>
                    @endforeach
                </tr>
                </tfoot>
            </table>


    </div>

    <div class="container px-5">
        <table class="table text-center">
            <thead>
            <tr>
                <th scope="col">Totale</th>
                <th scope="col">Straordinari</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td id="tot">XX.x</td>
                <td id="tot_straordinari">XX.x</td>
            </tr>
            <tr></tr>
            </tbody>
        </table>
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
            let tot = 0
            let tot_straordinari = 0
            $('#totale td').each((i, e) => {
                tot += parseInt($(e).text())
            })
            $('#straordinari td').each((i, e) => {
                tot_straordinari += parseInt($(e).text())
            })
            $('#tot').text(tot)
            $('#tot_straordinari').text(tot_straordinari)
        })
    </script>
@endsection