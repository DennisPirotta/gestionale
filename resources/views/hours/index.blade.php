@extends('layouts.app')
@section('content')
    {{-- Calendario --}}
    <div class="container my-3 p-5 shadow-sm">
        <div id="calendar"></div>
    </div>
    {{-- Modal aggiunta ore --}}
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
                        {{-- Quantita --}}
                        <div class="col-4">
                            <div class="input-group col-md-4 col-sm-6">
                                <span class="input-group-text"><i class="bi bi-clipboard-data me-2"></i>Ore</span>
                                <input type="text" class="form-control"  name="count" value="8">
                            </div>
                            @error('count')
                            <p class="text-danger fs-6">{{$message}}</p>
                            @enderror
                        </div>
                        {{-- Input vuoti per passaggio del giorno --}}
                        <label class="d-none">
                            <input type="date" id="day_start" name="day_start" value="">
                            <input type="date" id="day_end" name="day_end" value="">
                        </label>
                        {{-- Box selezione tipo di ora --}}
                        <div class="col-8">
                            <div class="input-group">
                                <label class="input-group-text" for="hour_type_id"><i class="bi bi-building me-2"></i>Tipologia</label>
                                <select class="form-select" id="hour_type_id" name="hour_type_id">
                                    <option value='' selected>Seleziona la tipologia</option>
                                    @foreach($hour_types as $hour_type)
                                        <option value="{{$hour_type->id}}">{{$hour_type->description !== "Ferie" ? $hour_type->description : "Permesso"}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('hour_type_id')
                            <p class="text-danger fs-6">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="flex-column d-flex col-12 d-none" id="contentDetails">
                            <hr class="mx-auto w-75 mb-3">
                            @role('admin|boss')
                            <small class="text-warning mx-auto"><i class="bi bi-exclamation-triangle"></i>Sezione dedicata all'inserimento dei dati</small>
                            <div class="input-group">
                                <label class="input-group-text" for="user_id"><i class="bi bi-person-badge me-2"></i>Utente</label>
                                <select class="form-select" id="user_id" name="user_id">
                                    <option value='' selected>Seleziona un dipendente</option>
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">{{ $user->name }} {{ $user->surname }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <hr class="mx-auto w-75 mb-3">
                            @endrole
                            {{-- Commesse --}}
                            <div class="details" id="content_1">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="input-group mb-3">
                                            <label class="input-group-text" for="job_type_id"><i
                                                        class="bi bi-gear me-2"></i>Tipo
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
                                            <label class="input-group-text" for="order_id"><i
                                                        class="bi bi-building me-2"></i>Commessa</label>
                                            <select class="form-select" id="order_id" name="order_id">
                                                <option value="" selected>Seleziona una commessa</option>
                                                @foreach($orders as $order)
                                                    <option value="{{$order->id}}"
                                                            class="bg-{{$order->status->color}} bg-opacity-50">
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

                                    <div class="col-12 d-none" id="job_description_box">
                                        <div class="input-group mb-3">
                                            <label class="input-group-text" for="job_description"><i
                                                        class="bi bi-info-circle me-2"></i>Descrizione</label>
                                            <input type="text" class="form-control" id="job_description"
                                                   name="job_description" value="{{ old('job_description') }}">
                                        </div>
                                        @error('job_description')
                                        <p class="text-danger fs-6">{{$message}}</p>
                                        @enderror
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="signed" id="timbrate"
                                                   value="0" checked>
                                            <label class="form-check-label" for="timbrate">
                                                Timbrate
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="signed" value="1"
                                                   id="modulo">
                                            <label class="form-check-label" for="modulo">
                                                Con Modulo
                                            </label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            {{-- Fogli intervento --}}
                            <div class="details" id="content_2">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="input-group mb-3">
                                            <label class="input-group-text" for="fi"><i
                                                        class="bi bi-clipboard me-2"></i>Tipo
                                                di F.I.</label>
                                            <select class="form-select" id="fi" name="fi_new">
                                                <option selected>Seleziona</option>
                                                <option value=0>
                                                    Nuovo
                                                </option>
                                                <option value=1>
                                                    Esistente
                                                </option>
                                            </select>
                                        </div>
                                        @error('fi')
                                        <p class="text-danger fs-6">{{$message}}</p>
                                        @enderror
                                    </div>
                                    <div class="hide" id="old_fi">
                                        <div class="col-12">
                                            <div class="input-group mb-3">
                                                <label class="input-group-text" for="number"><i
                                                            class="bi bi-building me-2"></i>Numero F.I</label>
                                                <select class="form-select" id="old_fi_number" name="fi_number">
                                                    @foreach($technical_reports as $technical_report)
                                                        <option value="{{$technical_report->id}}">
                                                            ({{$technical_report->number}})
                                                            - {{$technical_report->customer->name}}
                                                            @if(isset($technical_report->secondary_customer->name))
                                                                / {{ $technical_report->secondary_customer->name }}
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('old_fi_number')
                                            <p class="text-danger fs-6">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div id="new_fi" class="hide">
                                        <div class="col-12">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text"><i class="bi bi-list-ol me-2"></i>Numero</span>
                                                    <input type="text" class="form-control" aria-label="Numero" name="number" id="new_fi_number">
                                                </div>
                                        </div>
                                        @error('number')
                                        <p class="text-danger fs-6">{{$message}}</p>
                                        @enderror
                                        <div class="col-12">
                                            <div class="input-group mb-3">
                                                <label class="input-group-text" for="fi_order"><i
                                                            class="bi bi-building me-2"></i>Commessa</label>
                                                <select class="form-select" id="fi_order" name="fi_order_id">
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
                                                <label class="input-group-text" for="first_customer"><i
                                                            class="bi bi-person me-2"></i>Cliente</label>
                                                <select class="form-select" id="first_customer" name="customer_id" required>
                                                    @foreach($customers as $customer)
                                                        <option value="{{$customer->id}}">
                                                            {{$customer->name}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('first_customer')
                                            <p class="text-danger fs-6">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <div class="input-group mb-3">
                                                <label class="input-group-text" for="second_customer"><i
                                                            class="bi bi-people me-2"></i>Cliente
                                                    Secondario</label>
                                                <select class="form-select" id="second_customer" name="secondary_customer_id">
                                                    <option value="">Non presente</option>
                                                    @foreach($customers as $customer)
                                                        <option value="{{$customer->id}}">
                                                            {{$customer->name}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('second_customer')
                                            <p class="text-danger fs-6">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 d-flex justify-content-center mb-3">
                                        <div class="form-check mx-3">
                                            <input class="form-check-input" type="radio" name="night" id="night"
                                                   value="UE">
                                            <label class="form-check-label" for="night">
                                                Notte UE
                                            </label>
                                        </div>
                                        <div class="form-check mx-3">
                                            <input class="form-check-input" type="radio" name="night" id="night"
                                                   value="XUE">
                                            <label class="form-check-label" for="night">
                                                Notte Extra UE
                                            </label>
                                        </div>
                                        <div class="form-check mx-3">
                                            <input class="form-check-input" type="radio" name="night" id="night" checked
                                                   value="">
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
                            {{-- Assistenza --}}
                            <div class="details" id="content_3">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="input-group mb-3">
                                            <label class="input-group-text" for="help_customer"><i
                                                        class="bi bi-person me-2"></i>Cliente</label>
                                            <select class="form-select" id="help_customer" name="help_customer" required>
                                                @foreach($customers as $customer)
                                                    <option value="{{$customer->id}}">
                                                        {{$customer->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('first_customer')
                                        <p class="text-danger fs-6">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            {{-- Input Descrizione --}}
                            <div class="details" id="content_8">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-lg-12">
                                        <div class="input-group mb-3">
                                            <label class="input-group-text" for="description"><i
                                                        class="bi bi-building me-2"></i>Descrizione</label>
                                            <input type="text" class="form-control" name="description" id="description">
                                        </div>
                                        @error('description')
                                        <p class="text-danger fs-6">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="details" id="content_10">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-lg-12">
                                        <div class="input-group mb-3">
                                            <label class="input-group-text" for="description"><i
                                                        class="bi bi-building me-2"></i>Descrizione</label>
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
        $(() => {
            $('#fi').on('change', (event) => {
                let selected = $(event.target).val()
                $(`#new_fi_number`).prop('required',false)
                if (selected === '0') {
                    $('#new_fi_number').prop('required',true)
                    $('#new_fi').removeClass('hide')
                    $('#old_fi').addClass('hide')
                } else if (selected === '1'){
                    $('#new_fi').addClass('hide')
                    $('#old_fi').removeClass('hide')
                } else {
                    $('#new_fi').addClass('hide')
                    $('#old_fi').addClass('hide')
                }
            })

            $('#contentDetails').addClass("d-none")

            $('#job_type').on('change', function () {
                let el = $('#job_description_box');
                let val = $('#job_type').find(':selected').val()
                el.addClass("d-none")
                if (val == 5 || val == 7) el.removeClass("d-none")
            });

            $('#hour_type_id').on('change', function () {
                $('#new_fi_number').prop('required',false)
                switch ($('#hour_type_id').find(':selected').val()) {
                    case '': {
                        $('#contentDetails').addClass("d-none")
                        break
                    }
                    @foreach($hour_types as $hour_type)
                    case "{{$hour_type->id}}": {
                        $('#contentDetails').removeClass("d-none")
                        $('.details').addClass('d-none')
                        $('#content_{{$hour_type->id}}').removeClass("d-none")
                        break
                    }
                    @endforeach
                }
            });

            let hours = @json($hours, JSON_THROW_ON_ERROR);
            let calendarEl = document.getElementById('calendar')
            let rightContent = 'dayGridWeek dayGridMonth rimborsoMese'
            if(window.location.search === ''){
                @role('admin|boss')
                    rightContent += ' visualizzaTutto'
                @endrole
            }else{
                rightContent += ' clearURL'
            }
            let calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    left: 'prev next today showReport',
                    center: 'title',
                    right: rightContent

                },
                customButtons: {
                    rimborsoMese: {
                        text: 'Rimborso del mese',
                        click: function() {
                            window.location.href = '{{ route('expense_report.index') }}'
                        }
                    },
                    visualizzaTutto: {
                        text: 'Visualizza tutte le ore',
                        click: function () {
                            window.location.search = 'all=true'
                        }
                    },
                    clearURL: {
                        icon: 'bi-eraser',
                        click: function () {
                            window.location.href = '{{ route('hours.index') }}'
                        }
                    },
                    showReport: {
                        text: 'Report',
                        click: function (){
                            window.location.href = '{{ route('hours.report') }}?mese={{ \Carbon\Carbon::now()->format('Y-m') }}&user={{ auth()->id() }}'
                        }
                    }
                },
                businessHours: {
                    daysOfWeek: [1, 2, 3, 4, 5]
                },
                nowIndicator: true,
                locale: 'it',
                selectAllow: function (selectionInfo) {
                    let startDate = selectionInfo.start;
                    let endDate = selectionInfo.end;
                    endDate.setSeconds(endDate.getSeconds() - 1);
                    return !((startDate.getDay() == 0 || startDate.getDay() == 6) && (endDate.getDay() == 0 || endDate.getDay() == 6))
                },
                selectable: true,
                editable: true,
                allDaySlot: true,
                initialView: 'dayGridMonth',
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
                            trigger: 'click',
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
