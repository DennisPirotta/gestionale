@extends('layouts.app')
@section('content')
    <div class="modal fade" id="whereami" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Dove sei Oggi? <small class="ms-3" id="modal-date">{{ \Carbon\Carbon::today()->translatedFormat('D d M Y') }}</small></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('locations.store') }}" method="post" id="actionForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="sph-office" name="sph">
                            <label class="form-check-label" for="sph-office">
                                Presenza in ufficio a Chiasso
                            </label>
                        </div>

                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1"><i class="bi bi-geo-alt"></i></span>
                            <input type="text" class="form-control" name="whereami" id="position" placeholder="Dove sei?" aria-label="Dove sono" aria-describedby="basic-addon1" required>
                        </div>
                        <label>
                            <input type="date" class="d-none" name="date" id="date">
                        </label>
                        <label>
                            <input type="text" class="d-none" name="_method" id="_method">
                        </label>
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
    <div class="p-5 position-absolute" style="height: 90vh !important;width: 100vw">
        <div id='calendar' class="px-5 h-100 w-100"></div>
    </div>
    <script>
        $(document).ready(async function () {

            let calendarEl = document.getElementById('calendar')
            let events = @json($locations, JSON_THROW_ON_ERROR);
            let toggles = @json($toggles, JSON_THROW_ON_ERROR);

            let calendar = new Calendar(calendarEl, {
                plugins: [ dayGridPlugin,listPlugin,bootstrap5Plugin,rrulePlugin,interactionPlugin ],
                initialView: 'dayGridMonth',
                themeSystem: 'bootstrap5',
                selectable: false,
                locale: 'it',
                longPressDelay: 500,
                businessHours: {
                    daysOfWeek: [1, 2, 3, 4, 5], // Lunedì - Venerdì
                    startTime: '8:00',
                    endTime: '17:00',
                },
                customButtons: {
                    showAllLocations: {
                        icon: 'eye',
                        click: function() {
                            location.replace('{{ route('locations.index',['user' => auth()->id() ]) }}')
                        }
                    },
                    hideAllLocations: {
                        icon: 'eye-slash',
                        click: function() {
                            location.replace('{{ route('locations.index') }}')
                        }
                    }
                },
                headerToolbar: {
                    left: 'prev next today',
                    center: 'title',
                    right: 'listWeek dayGridMonth {{ request()->has('user') ? 'hideAllLocations' : 'showAllLocations' }}'
                },
                events: events,
                viewDidMount: (info) => {
                    let events = calendar.getEvents()
                    if (info.view.type == 'listWeek')
                        events.forEach(event => {
                            if (event.title == '') return
                            event.setProp('title', `${event.extendedProps.name} ${event.extendedProps.surname} - ${event.extendedProps.description}`)
                        })
                    if (info.view.type == 'dayGridMonth')
                        events.forEach(event => {
                            if (event.title == '') return
                            event.setProp('title', `${event.extendedProps.name.charAt(0)}.${event.extendedProps.surname.charAt(0)}. - ${event.extendedProps.description}`)
                        })
                },
                eventClick: (info) => {
                    $('#whereami').modal('toggle')
                    $('#position').val(info.event.extendedProps.description)
                    $('#actionForm').attr('action','{{ url('/dove-siamo') }}/'+info.event.extendedProps.locationId)
                    $('#_method').val('PUT')
                    $('#delete').removeClass('d-none')
                    $('#sph-office').parent().hide()
                },
                dateClick: (info) => {
                    $('#position').val('')
                    $('#actionForm').attr('action','{{ route('locations.store') }}')
                    $('#whereami').modal('toggle')
                    $('#date').val(info.dateStr)
                    $('#modal-date').text(moment(info.dateStr).locale('it').format('ddd D MMM YYYY'))
                    $('#_method').val('')
                    $('#sph-office').parent().show()
                    $('#sph-office').attr('checked',toggles.find(t => {return t === moment(info.date).format('YYYY-MM-DD')}) ? true : false)
                },
                dayCellDidMount : (info) => {
                    $(info.el).find('.fc-daygrid-day-number').prepend(`<span style="z-index:100;width: 10px;height: 10px;padding: 6px;background-color: ${ toggles.find(t => {return t === moment(info.date).format('YYYY-MM-DD')}) ? 'red' : '#10cc10' }" class="trigger me-2 d-inline-flex border border-light rounded-circle"><span class="visually-hidden">New alerts</span></span>`)
                }
            })
            calendar.render()
        })

        $('#whereami').on('hide.bs.modal',()=>{ $('#delete').addClass('d-none') })

        $('.toggle').click(()=>{
            console.log('click')
            $('#empty-office').modal('toggle')
        })

        $('#delete').click(()=>{
            $('#_method').val('DELETE')
        })
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js" integrity="sha384-qlmct0AOBiA2VPZkMY3+2WqkHtIQ9lSdAsAn5RUJD/3vA5MKDgSGcdmIv4ycVxyn" crossorigin="anonymous"></script>
@endsection
