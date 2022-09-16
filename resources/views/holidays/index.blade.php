@extends('layouts.app')
@section('content')
    <style>
        p{
            margin-bottom: 0;
        }
    </style>
    <div class="container mt-3 mb-1 shadow-sm p-5">
        <div class="row text-center">
            <div class="col-sm-12 col-lg-5 mb-3">
                <div class="card"> <!--  bg-black bg-opacity-25 -->
                    <div class="card-body">
                        <i class="bi bi-person fs-3"></i>
                        <span class="card-title fs-3">{{auth()->user()->name}}</span>
                        <hr class="w-50 mx-auto mt-0">

                        <div class="card-text" >
                            <p>Ore di ferie rimaste:
                            <b id="hourLeft">{{$left_hours}}</b>
                            ( <b id="daysLeft">{{$left_hours/8}}</b> Giorni )
                            <div id="progress" class="my-3 w-25 mx-auto"></div>
                            <form method="POST" action="{{ route('holidays.destroyMore') }}">
                                @csrf
                                <div class="h-100 overflow-auto mb-3 fancy-scrollbar" style="max-height: 15vw">

                                    <table class="table text-center ">
                                        <thead>
                                        <tr>
                                            <th scope="col" id="header" style="cursor:pointer;">#</th>
                                            <th scope="col">Inizio</th>
                                            <th scope="col">Fine</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php($count = 1)
                                            @foreach($holidays as $event)
                                                @if($event['user'] === auth()->id())
                                                    @php($start = \Carbon\Carbon::createFromTimeString($event['start']))
                                                    @php($end = \Carbon\Carbon::createFromTimeString($event['end']))
                                                    <tr>
                                                        <th scope="row">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" value="{{$event['id']}}" id="{{$event['id']}}" name="ferie[]">
                                                                <label class="form-check-label" for="{{$event['id']}}"></label>
                                                            </div>
                                                        </th>
                                                        @if($event['allDay'])
                                                            <td id="start_{{ $event['id'] }}">
                                                                <p>{{ $start->translatedFormat('D d F Y') }}</p>
                                                            </td>
                                                            <td id="end_{{ $event['id'] }}">
                                                                <p>{{ $end->translatedFormat('D d F Y') }}</p>
                                                            </td>
                                                        @else
                                                            <td id="start_{{ $event['id'] }}">
                                                                <p>{{ $start->translatedFormat('D d F Y') }}</p>
                                                                <p>{{ $start->translatedFormat('H:i') }}</p>
                                                            </td>
                                                            <td id="end_{{ $event['id'] }}">
                                                                <p>{{ $end->translatedFormat('D d F Y') }}</p>
                                                                <p>{{ $end->translatedFormat('H:i') }}</p>
                                                            </td>
                                                        @endif

                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
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
                    <label for="allDay"></label><input name="allDay" class="d-none">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="input-group mb-3 col-md-4 col-sm-6">
                                    <span class="input-group-text"><i class="bi bi-calendar me-2"></i>Inizio</span>
                                    <input type="date" class="form-control" aria-label="Inizio" name="start" id="start" value="{{ old('start') }}" required>
                                </div>
                                @error('start')
                                <p class="text-danger fs-6">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="input-group mb-3 col-md-4 col-sm-6">
                                    <span class="input-group-text"><i class="bi bi-calendar me-2"></i>Fine</span>
                                    <input type="date" class="form-control" aria-label="Fine" name="end" id="end" value="{{ old('end') }}" required>
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

        $(document).ready(async function () {

            let progressBar = new ProgressBar.Circle('#progress', {
                color: '#000000',
                // This has to be the same size as the maximum width to
                // prevent clipping
                strokeWidth: 4,
                trailWidth: 1,
                easing: 'easeInOut',
                duration: 1400,
                from: { color: '#FF0000', width: 1 },
                to: { color: '#00FF00', width: 4 },
                // Set default step function for all animate calls
                step: function(state, circle) {
                    circle.path.setAttribute('stroke', state.color);
                    circle.path.setAttribute('stroke-width', state.width);

                    let value = Math.round(circle.value() * 100);
                    if (value === 0) {
                        circle.setText('');
                    } else {
                        circle.setText(value + "%");
                    }

                }
            })
            progressBar.text.style.fontFamily = '"Raleway", Helvetica, sans-serif';
            progressBar.text.style.fontSize = '1.5rem';
            progressBar.animate({{\App\Models\Holiday::getLeftHours() / auth()->user()->holidays}})
            let events = @json($holidays, JSON_THROW_ON_ERROR);
            let calendarEl = document.getElementById('calendar')
            let calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                themeSystem: 'bootstrap5',
                selectable: true,
                editable: true,
                slotDuration: '1:00',
                slotMinTime: '8:00',
                slotMaxTime: '18:00',
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
                    right: 'listWeek timeGridDay dayGridMonth'
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
                    $('input[name="allDay"]').val(info.allDay)
                    let inputStart = $('input[name="start"]')
                    let inputEnd = $('input[name="end"]')
                    if (info.allDay){
                        inputStart.attr('type','date')
                        inputEnd.attr('type','date')
                        inputStart.val(info.startStr)
                        inputEnd.val(info.endStr)
                    }else {
                        inputStart.attr('type','time')
                        inputEnd.attr('type','time')
                        inputStart.val(moment(info.start).format('HH:mm'))
                        inputEnd.val(moment(info.end).format('HH:mm'))
                    }
                },
                eventResize: async (info) => {
                    let progress = updateEvent(info)
                    let start = new moment(info.event.start)
                    let end = new moment(info.event.end)
                    progressBar.animate(await progress)
                    $(`#start_${info.event.id}`).text(start.format('ddd DD MMMM YYYY'))
                    $(`#end_${info.event.id}`).text(end.format('ddd DD MMMM YYYY'))
                }
            })
            calendar.render()
            $("#header").click(function(){
                $('input:checkbox').prop('checked', true);
            });
        })
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/progressbar.js/1.1.0/progressbar.min.js" integrity="sha512-EZhmSl/hiKyEHklogkakFnSYa5mWsLmTC4ZfvVzhqYNLPbXKAXsjUYRf2O9OlzQN33H0xBVfGSEIUeqt9astHQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link href="https://fonts.googleapis.com/css?family=Raleway:400,300,600,800,900" rel="stylesheet" type="text/css">
@endsection
