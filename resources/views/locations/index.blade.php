@extends('layouts.app')
@section('content')
    <div class="modal fade" id="whereami" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Dove sei Oggi? <small class="ms-3">{{ \Carbon\Carbon::today()->translatedFormat('D d M Y') }}</small></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('locations.store') }}" method="post" id="actionForm">
                    @csrf
                    <label>
                        <input type="date" class="d-none" name="date" id="date">
                    </label>
                    <label>
                        <input type="text" class="d-none" name="_method" id="_method">
                    </label>
                    <div class="modal-body">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1"><i class="bi bi-geo-alt"></i></span>
                            <input type="text" class="form-control" name="whereami" id="position" placeholder="Dove sei?" aria-label="Dove sono" aria-describedby="basic-addon1" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                        <button type="submit" class="btn btn-primary">Salva</button>
                        <button id="delete" type="submit" class="btn btn-danger d-none" data-bs-dismiss="modal">Elimina</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    @if(session()->has('whereami'))
        <script>
            $(() => {
                let date = new moment()
                $('#whereami').modal('toggle')
                $('#date').val(date.format('YYYY-MM-DD'))
            })
        </script>
    @endif
    <div class="container mt-3 mb-1 shadow-sm p-5">
        <div id='calendar'></div>
    </div>
    <script>
        $(document).ready(async function () {

            let calendarEl = document.getElementById('calendar')
            let events = @json($locations, JSON_THROW_ON_ERROR);
            let calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                themeSystem: 'bootstrap5',
                selectable: true,
                contentHeight: 700,
                locale: 'it',
                longPressDelay: 500,
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
                viewDidMount: (info) => {
                    let events = calendar.getEvents()
                    if (info.view.type == 'listWeek')
                        events.forEach(event => event.setProp('title',`${event.extendedProps.name} ${event.extendedProps.surname} - ${event.extendedProps.description}`))
                    if (info.view.type == 'dayGridMonth')
                        events.forEach(event => event.setProp('title',`${event.extendedProps.name.charAt(0)}.${event.extendedProps.surname.charAt(0)}. - ${event.extendedProps.description}`))
                },
                eventClick: (info) => {
                    $('#whereami').modal('toggle')
                    $('#position').val(info.event.extendedProps.description)
                    $('#actionForm').attr('action','{{ url('/dove-siamo') }}/'+info.event.extendedProps.locationId)
                    $('#_method').val('PUT')
                    $('#delete').removeClass('d-none')
                },
                select: (info) => {
                    $('#actionForm').attr('action','{{ route('locations.store') }}')
                    $('#whereami').modal('toggle')
                    $('#date').val(info.startStr)
                    $('#_method').val('')
                }
            })
            calendar.render()
        })

        $('#whereami').on('hide.bs.modal',()=>{ $('#delete').addClass('d-none') })

        $('#delete').click(()=>{
            $('#_method').val('DELETE')
        })
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js" integrity="sha384-qlmct0AOBiA2VPZkMY3+2WqkHtIQ9lSdAsAn5RUJD/3vA5MKDgSGcdmIv4ycVxyn" crossorigin="anonymous"></script>
@endsection
