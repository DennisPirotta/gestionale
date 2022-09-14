@extends('layouts.app')
@php($require_navbar_tools = true)
@section('content')
    <div class="container my-3 p-5 shadow-sm">
        <div id="calendar"></div>
    </div>
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
                        <div class="col-sm-6 col-md-6">
                            <div class="input-group mb-3 col-md-4 col-sm-6">
                                <span class="input-group-text"><i class="bi bi-clipboard-data me-2"></i>Inizio</span>
                                <input type="time" class="form-control" aria-label="Inizio" name="start" value="08:00">
                            </div>
                            @error('start')
                            <p class="text-danger fs-6">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="input-group mb-3 col-md-4 col-sm-6">
                                <span class="input-group-text"><i class="bi bi-clipboard-data me-2"></i>Fine</span>
                                <input type="time" class="form-control" aria-label="Fine" name="end" value="17:00">
                            </div>
                            @error('end')
                            <p class="text-danger fs-6">{{$message}}</p>
                            @enderror
                        </div>
                        <label class="d-none">
                            <input type="date" id="day_start" name="day_start" value="">
                            <input type="date" id="day_end" name="day_end" value="">
                        </label>
                        <div class="col-12">
                            <div class="input-group mb-3">
                                <label class="input-group-text" for="hour_type_id"><i class="bi bi-building me-2"></i>Tipologia</label>
                                <select class="form-select" id="hour_type_id" name="hour_type_id">
                                    <option value='' selected>Seleziona la tipologia</option>
                                    @foreach($hour_types as $hour_type)
                                        <option value="{{$hour_type->id}}">{{$hour_type->description}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('hour_type_id')
                            <p class="text-danger fs-6">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="flex-column d-flex col-12 visually-hidden" id="contentDetails">
                            <hr class="mx-auto w-75 mb-3">
                            <div class="details" id="content_1" > {{-- contenuto per commesse --}}
                                <div class="row">
                                    <div class="col-12">
                                        <div class="input-group mb-3">
                                            <label class="input-group-text" for="job_type_id"><i class="bi bi-gear me-2"></i>Tipo
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
                                            <label class="input-group-text" for="order_id"><i class="bi bi-building me-2"></i>Commessa</label>
                                            <select class="form-select" id="order_id" name="order_id">
                                                <option value="" selected>Seleziona una commessa</option>
                                                @foreach($orders as $order)
                                                    <option value="{{$order->id}}" class="bg-{{$order->status->color}} bg-opacity-50">
                                                        ({{$order->innerCode}})
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

                                    <div class="col-12 visually-hidden" id="job_description_box">
                                        <div class="input-group mb-3">
                                            <label class="input-group-text" for="job_description"><i class="bi bi-info-circle me-2"></i>Descrizione</label>
                                            <input type="text" class="form-control" id="job_description" name="job_description" value="{{ old('job_description') }}">
                                        </div>
                                        @error('job_description')
                                        <p class="text-danger fs-6">{{$message}}</p>
                                        @enderror
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="extra" id="timbrate" value="0" checked>
                                            <label class="form-check-label" for="timbrate">
                                                Timbrate
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="extra" value="1" id="modulo">
                                            <label class="form-check-label" for="modulo">
                                                Con Modulo
                                            </label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="details" id="content_2"> {{-- foglio intervento --}}
                                <div class="row">
                                    <div class="col-12">
                                        <div class="input-group mb-3">
                                            <label class="input-group-text" for="fi"><i class="bi bi-clipboard me-2"></i>Tipo
                                                di F.I.</label>
                                            <select class="form-select" id="fi" name="fi">
                                                <option value="" selected>Seleziona</option>
                                                <option value=true>
                                                    Nuovo
                                                </option>
                                                <option value=false>
                                                    Esistente
                                                </option>
                                            </select>
                                        </div>
                                        @error('fi')
                                        <p class="text-danger fs-6">{{$message}}</p>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <div class="input-group mb-3">
                                            <label class="input-group-text" for="fi_order"><i class="bi bi-building me-2"></i>Commessa</label>
                                            <select class="form-select" id="fi_order" name="fi_order">
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
                                            <label class="input-group-text" for="customer"><i class="bi bi-person me-2"></i>Cliente</label>
                                            <select class="form-select" id="customer" name="customer">
                                                @foreach($customers as $customer)
                                                    <option value="{{$customer->id}}">
                                                        {{$customer->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('customer')
                                        <p class="text-danger fs-6">{{$message}}</p>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <div class="input-group mb-3">
                                            <label class="input-group-text" for="customer2"><i class="bi bi-people me-2"></i>Cliente
                                                Secondario</label>
                                            <select class="form-select" id="customer2" name="customer2">
                                                <option value="">Non presente</option>
                                                @foreach($customers as $customer)
                                                    <option value="{{$customer->id}}">
                                                        {{$customer->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('customer2')
                                        <p class="text-danger fs-6">{{$message}}</p>
                                        @enderror
                                    </div>
                                    <div class="col-12 d-flex justify-content-center mb-3">
                                        <div class="form-check mx-3">
                                            <input class="form-check-input" type="radio" name="night" id="night" value="UE">
                                            <label class="form-check-label" for="night">
                                                Notte UE
                                            </label>
                                        </div>
                                        <div class="form-check mx-3">
                                            <input class="form-check-input" type="radio" name="night" id="night" value="XUE">
                                            <label class="form-check-label" for="night">
                                                Notte Extra UE
                                            </label>
                                        </div>
                                        <div class="form-check mx-3">
                                            <input class="form-check-input" type="radio" name="night" id="night" checked value="">
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
                            <div class="details" id="content_8"> {{-- foglio intervento --}}
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-lg-12">
                                        <div class="input-group mb-3">
                                            <label class="input-group-text" for="description"><i class="bi bi-building me-2"></i>Descrizione</label>
                                            <input type="text" class="form-control" name="description" id="description">
                                        </div>
                                        @error('description')
                                        <p class="text-danger fs-6">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="details" id="content_10"> {{-- foglio intervento --}}
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-lg-12">
                                        <div class="input-group mb-3">
                                            <label class="input-group-text" for="description"><i class="bi bi-building me-2"></i>Descrizione</label>
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
        $(function () {

            $('#contentDetails').addClass("visually-hidden")

            $('#job_type').on('change', function () {
                let el = $('#job_description_box');
                let val = $('#job_type').find(':selected').val()
                el.addClass("visually-hidden")
                if (val == 5 || val == 7) el.removeClass("visually-hidden")
            });

            $('#hour_type_id').on('change', function () {
                switch ($('#hour_type_id').find(':selected').val()) {
                    case '': {
                        $('#contentDetails').addClass("visually-hidden")
                        break
                    }
                    @foreach($hour_types as $hour_type)
                    case "{{$hour_type->id}}": {
                        $('#contentDetails').removeClass("visually-hidden")
                        $('.details').addClass('visually-hidden')
                        $('#content_{{$hour_type->id}}').removeClass("visually-hidden")
                        break
                    }
                    @endforeach
                }
            });

            let hours = @json($hours, JSON_THROW_ON_ERROR);
            let calendarEl = document.getElementById('calendar')
            let calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    left: 'prev next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridDay,dayGridWeek'
                },
                nowIndicator: true,
                locale: 'it',
                slotDuration: '1:00',
                slotMinTime: '8:00',
                slotMaxTime: '18:00',
                selectable: true,
                editable: true,
                allDaySlot: true,
                initialView: 'dayGridWeek',
                themeSystem: 'bootstrap5',
                events: hours,
                select: (info) => {
                    $('#day_start').val(new moment(info.start).format('YYYY-MM-DD'))
                    $('#day_end').val(new moment(info.end).format('YYYY-MM-DD'))
                    $('#myModal').modal('toggle')
                },
                eventDidMount: function (info) {
                    $(info.el).popover(
                        {
                            title: 'Dettagli',
                            placement: 'top',
                            trigger: 'hover',
                            content: info.event.extendedProps.content,
                            container: 'body',
                            html: true,
                            sanitize: false,
                            role: 'button'
                        }
                    ).popover().show()
                },
            })
            calendar.render()
        })
    </script>
@endsection
