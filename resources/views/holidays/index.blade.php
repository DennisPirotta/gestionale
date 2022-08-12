@extends('layouts.app')
@section('content')
    <div class="container mt-3 mb-1 shadow-sm p-5">
        <div class="row text-center">
            <div class="col-sm-12 col-lg-5 mb-3">
                <div class="card"> <!--  bg-black bg-opacity-25 -->
                    <div class="card-body">
                        <i class="bi bi-person fs-3"></i>
                        <span class="card-title fs-3">{{auth()->user()->name}}</span>
                        <hr class="w-50 mx-auto mt-0">

                        <p class="card-text">
                            Ore di ferie rimaste:
                            <b id="hourLeft">{{$left_hours}}</b>
                            ( <b id="daysLeft">{{$left_hours/8}}</b> Giorni )
                        </p>

                        <div class="progress my-3">
                            <div id="progressBar" class="progress-bar" role="progressbar"
                                 aria-label="Example with label"
                                 style="width: {{($left_hours*100)/160}}%" aria-valuenow="50" aria-valuemin="0"
                                 aria-valuemax="100">
                                {{($left_hours*100)/160}}% rimasto
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <a href="#">
                                <button class="btn btn-outline-primary me-2">
                                    <i class="bi bi-pencil-square me-1"></i>
                                    Modifica
                                </button>
                            </a>

                            <form method="POST" action="#">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger"
                                        onclick="return confirm('Sicuro di voler Eliminare?')">
                                    <i class="bi bi-trash me-1"></i>
                                    Elimina
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-lg-7">
                <div id='calendar'></div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="label">Inserimento Ferie</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="/ferie">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="input-group mb-3 col-md-4 col-sm-6">
                                    <span class="input-group-text"><i class="bi bi-calendar me-2"></i>Inizio</span>
                                    <input type="date" class="form-control" aria-label="Inizio" name="start" required>
                                </div>
                                @error('start')
                                <p class="text-danger fs-6">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="input-group mb-3 col-md-4 col-sm-6">
                                    <span class="input-group-text"><i class="bi bi-calendar me-2"></i>Fine</span>
                                    <input type="date" class="form-control" aria-label="Fine" name="end" required>
                                </div>
                                @error('end')
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
    <script>
        $(document).ready(function () {
            let events = @json($holidays, JSON_THROW_ON_ERROR);
            let calendarEl = document.getElementById('calendar')
            let calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                themeSystem: 'bootstrap5',
                selectable: true,
                editable: true,
                locale: 'it',
                longPressDelay: 1000,
                businessHours: {
                    daysOfWeek: [1, 2, 3, 4, 5], // Lunedì - Venerdì
                    startTime: '8:00',
                    endTime: '17:00',
                },
                headerToolbar: {
                    left: 'prev next today',
                    center: 'title',
                    right: 'listWeek timeGridWeek dayGridMonth'
                },
                events: events,
                eventDidMount: function (info) {
                    let content = `<form method="POST" action="/ferie/${info.event.id}">@csrf @method('DELETE')<button class="btn btn-outline-danger" onclick="return confirm('Sicuro di voler Eliminare?')"><i class="bi bi-trash me-1 fs-4"></i></button></form>`
                    $(info.el).popover(
                        {
                            title: 'Dettagli',
                            placement: 'top',
                            trigger: 'click',
                            content: content,
                            container: 'body',
                            html: true,
                            sanitize: false,
                            role: 'button'
                        }
                    ).popover().show()
                },
                eventDrop: updateEvent,
                select: function (info) {
                    $('#myModal').modal('toggle')
                    $('input[name="start"]').val(info.startStr)
                    $('input[name="end"]').val(info.endStr)
                },
                eventResize: updateEvent
            })
            calendar.render()
        })
    </script>
@endsection
