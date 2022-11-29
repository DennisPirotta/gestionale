@extends('layouts.app')
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>

<script>
    localStorage.theme = 'light'
    tailwind.config = {
        darkMode: 'class',
        theme: {
            extend: {
                colors: {
                    clifford: '#da373d',
                }
            }
        }
    }
</script>
<style type="text/tailwindcss">
    @layer utilities {
        .content-auto {
            content-visibility: auto;
        }
    }
</style>
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
    }
</style> <!-- Stili per stampa -->
@section('content')
    <div class="container-fluid px-5 mt-5 table-responsive">
        <div class="d-flex align-items-center">
            <div class="h1 m-0">
                Ore
                {{ request('user') !== null ? App\Models\User::find(request('user'))->name : auth()->user()->name }}
                -
                {{ request('month') !== null ? Carbon\Carbon::parse(request('month'))->translatedFormat('F Y') : __('Select a month') }}
            </div>
            <div class="no-print flex ml-auto">
                <button class="btn btn-primary me-2 ms-auto"
                        onclick="window.location.href = '{{ route('expense_report.index') }}'"><i
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
                    @endrole
                </form>
            </div>


        </div>
        <hr class="hr my-3">
        @unless($data->count() === 0 || !request('month'))
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 text-center">
                <thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="py-2 px-4">
                        #
                    </th>
                    @foreach($period as $day)
                        <th scope="col"
                            class="py-2 px-4 border-l border-gray-300 dark:border-gray-500">{{ $day->format('j') }}</th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @foreach($data as $desc=>$type)
                    <tr class="bg-gray-50 dark:bg-gray-500 dark:border-gray-800">
                        <td class="py-2 px-4 dark:bg-gray-800 border-l">{{ $desc }}</td>
                        <td colspan="{{$period->count()}}" class="py-2 px-4 dark:bg-gray-800"></td>
                    </tr>
                    @foreach($type as $key=>$content)
                        @foreach($content as $job_type=>$hours)
                            <tr class="bg-gray-100 dark:bg-gray-900 dark:border-gray-800 border-b">
                                <th scope="row"
                                    class="border-r dark:border-gray-700 p-1.5 grid grid-cols-1 place-items-center">
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
                                    <div class="w-15 dark:bg-blue-100 bg-blue-300 text-blue-800 text-xs font-medium px-2 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800">
                                        {{ $job_type }}
                                    </div>
                                </th>
                                @foreach($period as $day)
                                    <td class="border-r dark:border-gray-700"
                                        data-datetime="{{ $day->format('Y-m-d') }}"
                                        data-row="{{ $hour_types->where('description',$desc)->value('id') }}"
                                        data-extra="{{ $key }}"
                                        data-job="{{ $job_types->where('title',$job_type)->value('id') }}"
                                        contenteditable="true"
                                        @foreach($hours as $hour)
                                            @if($hour->date === $day->format('Y-m-d'))
                                                data-hour="{{ $hour->id }}">
                                        {{ $hour->count }}
                                        @endif
                                        @endforeach
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    @endforeach
                @endforeach
                </tbody>
            </table>
    </div>
    @php($user = App\Models\User::find(request('user')) ?? auth()->user())
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
            <img src="{{ asset('images/no-orders.svg') }}" alt="" style="width: 40rem">
            <div class="fs-1">Seleziona un utente e un mese</div>
        </div>
        </div>
    @endunless

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
                let url = '{{ route('hours.store') }}'
                let method = 'POST'
                if ($(e.target).attr('data-hour')) {
                    url = '/ore/' + $(e.target).attr('data-hour')
                    if ($(e.target).text().trim() === '' || $(e.target).text().trim() === '0') method = 'DELETE'
                    else method = 'PUT'
                }
                const data = {
                    '_token': token,
                    '_method': method,
                    'count': $(e.target).text().trim(),
                    'date': $(e.target).attr('data-datetime'),
                    'description': null,
                    'hour_type_id': $(e.target).attr('data-row'),
                    'extra': $(e.target).attr('data-extra'),
                    'job': $(e.target).attr('data-job'),
                    'hour': $(e.target).attr('data-hour')
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
                })
            })
            cells.keypress(e => {
                if (e.which < 48 || e.which > 57) {
                    e.preventDefault();
                }
                if (e.keyCode === 13) {
                    e.preventDefault();
                    $(e.target).blur()
                }
            })
        })
    </script>
@endsection