@php use App\Models\Holiday;use Carbon\Carbon; use Carbon\CarbonPeriod@endphp
@extends('layouts.app')
@section('content')

    <style>
        .fc-list-event-dot{
            margin-top: 5px;
        }
    </style>

    <div class="shadow-sm mx-5 mt-3 p-3 max-h-[85vh] relative">
        <div id="calendar2"></div>
    </div>
    @role('admin|boss')
    @if(($count = $holidays->where('approved',false)->count()) > 0)
        <div class="container mt-5 mb-3 shadow-sm p-5 table-responsive" id="approvare">
            <h2 class="text-center">{{ $count }} Ferie / {{ $count > 1 ? 'Permessi' : 'Permesso' }} da approvare</h2>
            <hr>
            <table class="table align-middle mt-3">
                <thead>
                <tr>
                    <th scope="col">Utente</th>
                    <th scope="col">Inizio</th>
                    <th scope="col">Fine</th>
                    <th scope="col">Azioni</th>
                </tr>
                </thead>
                <tbody>
                @foreach( $users as $user )
                    @foreach($user->holidayList->where('approved',false) as $holiday)
                        <tr>
                            <th scope="row">{{ $user->surname }} {{ $user->name }}</th>
                            <td>{{ Carbon::parse($holiday->start)->translatedFormat($holiday->permission ? 'D d M Y H:i' : 'D d M Y') }}</td>
                            <td>{{ Carbon::parse($holiday->end)->translatedFormat($holiday->permission ? 'D d M Y H:i' : 'D d M Y') }}</td>
                            <td>
                                <form method="POST" action="{{ route('holidays.approve',$holiday->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success"
                                            onclick="return confirm('Approvate le ferie?')"><i
                                            class="bi bi-check fs-4"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
    @endrole
    <script>
        $(()=>{
            let events = @json($events, JSON_THROW_ON_ERROR);
            let calendarEl = document.getElementById('calendar2')
            let calendar = new Calendar(calendarEl, {
                plugins: [ dayGridPlugin,listPlugin,bootstrap5Plugin,rrulePlugin,interactionPlugin ],
                initialView: 'dayGridMonth',
                themeSystem: 'bootstrap5',
                slotDuration: '1:00',
                selectable: true,
                slotMinTime: '8:00',
                slotMaxTime: '18:00',
                locale: 'it',
                height: '80vh',
                longPressDelay: 1000,
                businessHours: {
                    daysOfWeek: [1, 2, 3, 4, 5], // Lunedì - Venerdì
                    startTime: '8:00',
                    endTime: '17:00',
                },
                headerToolbar: {
                    left: 'title',
                    center: '',
                    right: 'dayGridMonth listMonth today prev next'
                },
                events: events,
                eventDidMount: function (info) {
                    let content = `
                        <div>
                            <p>Richiesta di <b>${ info.event.allDay ? 'ferie' : 'permesso' }</b></p>
                            <p>Da <b>${ moment(info.event.start).locale('it').format('dddd D MMMM YYYY') } ${info.event.allDay ?'': moment(info.event.start).locale('it').format('H:mm')}</b></p>
                            <p>A <b>${ moment(info.event.end).locale('it').format('dddd D MMMM YYYY') } ${info.event.allDay ?'': moment(info.event.end).locale('it').format('H:mm')}</b></p>
                        </div>
                        <div class="d-flex justify-content-center">
                            <form method="POST" action="/ferie/${info.event.id}" class="mb-0">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger" onclick="return confirm('Sicuro di voler Eliminare?')">
                                    <i class="bi bi-trash me-1 fs-4"></i>
                                </button>
                            </form>
                            <button class="ms-2 btn btn-outline-primary">
                                    <i class="bi bi-pencil me-1 fs-4"></i>
                            </button>
                        </div>
                        `
                    if (info.event.extendedProps.user === {{ auth()->id() }} || {{ auth()->user()->hasRole('admin|boss') }} )
                        $(info.el).popover(
                            {
                                title: 'Dettagli',
                                placement: 'top',
                                content: content,
                                html: true,
                                sanitize: false,
                                role: 'button',
                            }
                        ).popover().show()
                },
                select: function (info) {
                    $('#myModal').modal('toggle')
                    let inputStart = $('input[name="start"]')
                    let inputEnd = $('input[name="end"]')
                    inputStart.val(info.startStr)
                    if (info.end - info.start !== 86400000){
                        inputEnd.parent().show()
                        inputEnd.attr('disabled',false)
                        inputEnd.val(info.endStr)
                        $('input[name="request_type"]').val('holidays')
                        $("#permission_end_box").addClass('d-none')
                        $("#permission_end").prop('disabled',true)
                        $("#permission_start_box").addClass('d-none')
                        $("#permission_start").prop('disable',true)
                        $("#title").text('Inserimento Ferie')
                        $("#alert").show()
                    }else{
                        inputEnd.parent().hide()
                        inputEnd.attr('disabled',true)
                        $('input[name="request_type"]').val('permission')
                        $("#permission_end_box").removeClass('d-none')
                        $("#permission_end").prop('disabled',false)
                        $("#permission_start_box").removeClass('d-none')
                        $("#permission_start").prop('disabled',false)
                        $("#title").text('Inserimento Permesso')
                        $("#alert").hide()
                    }

                }
            })
            calendar.render()
            let headers = $('.fc-header-toolbar').children()
            $(headers[1]).html('<h3 class="mb-0"><b>{{ round($left_hours,1) }}</b> Ore di ferie rimanenti</h3>')
            $("#header").click(function () {
                $('input:checkbox').prop('checked', true);
            });

        })
    </script>
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="title" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="title">Inserimento Ferie</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="/ferie">
                    @csrf
                    <input type="hidden" name="request_type">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12" id="alert">
                                <div class="alert alert-primary" role="alert">
                                    <i class="bi bi-info-circle me-2"></i>Le date indicate fanno riferimento al giorno d'inizio <b>( compreso )</b> delle ferie e il giorno di fine <b>(escluso)</b>
                                </div>
                            </div>
                            <div class="col-12 d-flex">
                                <div class="input-group mb-3">
                                    <span class="input-group-text"><i class="bi bi-calendar me-2"></i>Inizio</span>
                                    <input type="date" class="form-control" aria-label="Inizio" name="start" id="start"
                                           value="{{ old('start') }}" required>
                                </div>
                                @error('start')
                                <p class="text-danger fs-6">{{$message}}</p>
                                @enderror
                                <div class="input-group mb-3 ms-2">
                                    <span class="input-group-text"><i class="bi bi-calendar me-2"></i>Fine</span>
                                    <input type="date" class="form-control" aria-label="Fine" name="end" id="end"
                                           value="{{ old('end') }}" required>
                                </div>
                                @error('end')
                                <p class="text-danger fs-6">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="d-none col-md-6 col-sm-12 mt-2" id="permission_start_box">
                                <div class="input-group mb-3 col-md-4 col-sm-6">
                                    <span class="input-group-text"><i class="bi bi-clock me-2"></i>Inizio</span>
                                    <input type="time" class="form-control" aria-label="Inizio" name="permission_start" id="permission_start"
                                           value="{{ old('permission_start') }}" disabled required>
                                </div>
                                @error('permission_start')
                                <p class="text-danger fs-6">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="col-md-6 col-sm-12 mt-2" id="permission_end_box">
                                <div class="input-group mb-3 col-md-4 col-sm-6">
                                    <span class="input-group-text"><i class="bi bi-clock me-2"></i>Fine</span>
                                    <input type="time" class="form-control" aria-label="Fine" name="permission_end" id="permission_end"
                                           value="{{ old('permission_end') }}" disabled required>
                                </div>
                                @error('permission_end')
                                <p class="text-danger fs-6">{{$message}}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                        <button type="submit" class="btn btn-primary">Salva</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
