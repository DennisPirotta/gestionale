@extends('layouts.app')
@php($require_navbar_tools = true)
@section('content')

    <div class="container my-5 shadow-sm p-5">
        <form method="post" action="/ore" class="row">
            @csrf
            <div class="col-sm-6 col-md-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <span class="input-group-text"><i class="bi bi-clipboard-data me-2"></i>Inizio</span>
                    <input type="datetime-local" class="form-control" aria-label="Inizio" name="start">
                </div>
                @error('start')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <span class="input-group-text"><i class="bi bi-clipboard-data me-2"></i>Fine</span>
                    <input type="datetime-local" class="form-control" aria-label="Fine" name="end">
                </div>
                @error('end')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class="col-12">
                <div class="input-group mb-3">
                    <label class="input-group-text" for="hour_type"><i class="bi bi-building me-2"></i>Tipologia</label>
                    <select class="form-select" id="hour_type" name="hour_type">
                        <option value='' selected>Seleziona la tipologia</option>
                        @foreach($hour_types as $hour_type)
                            <option value="{{$hour_type->id}}">{{$hour_type->description}}</option>
                        @endforeach
                    </select>
                </div>
                @error('hour_type')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class="flex-column d-flex col-12 visually-hidden" id="contentDetails">
                <hr class="mx-auto w-75 my-5">
                <div class="details" id="content_1" > {{-- contenuto x commesse --}}
                    <div class="row">
                        <div class="col-md-6 col-sm-12 col-lg-4">
                            <div class="input-group mb-3">
                                <label class="input-group-text" for="year"><i
                                        class="bi bi-building me-2"></i>Anno</label>
                                <input type="number" id="year" name="year" class="form-control" min="1970" max="2099"
                                       step="1" value="{{date('Y')}}"/>
                            </div>
                            @error('year')
                            <p class="text-danger fs-6">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="col-md-6 col-sm-12 col-lg-4">
                            <div class="input-group mb-3">
                                <label class="input-group-text" for="job_type"><i class="bi bi-building me-2"></i>Tipo
                                    di lavoro</label>
                                <select class="form-select" id="job_type" name="job_type">
                                    @foreach($job_types as $job_type)
                                        <option
                                            value="{{$job_type->id}}"
                                            class="bg-{{$job_type->color}} bg-opacity-50">
                                            {{$job_type->description}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('job_type')
                            <p class="text-danger fs-6">{{$message}}</p>
                            @enderror
                        </div>

                        <div class="col-md-6 col-sm-12 col-lg-4">
                            <div class="input-group mb-3">
                                <label class="input-group-text" for="order"><i class="bi bi-building me-2"></i>Commessa</label>
                                <select class="form-select" id="order" name="order">
                                    @foreach($orders as $order)
                                        <option
                                            value="{{$order->id}}"
                                            class="bg-{{$statuses->where('id',$order->status)->value('color')}} bg-opacity-50">
                                            ({{$order->innerCode}})
                                            - {{$customers->where('id',$order->customer)->value('name')}}
                                            [{{$statuses->where('id',$order->status)->value('description')}}]
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('order')
                            <p class="text-danger fs-6">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="details" id="content_2"> {{-- foglio intervento --}}
                    <div class="row">
                        <div class="col-md-6 col-sm-12 col-lg-4">
                            <div class="input-group mb-3">
                                <label class="input-group-text" for="fi"><i class="bi bi-building me-2"></i>Tipo
                                    di F.I.</label>
                                <select class="form-select" id="fi" name="fi">
                                    <option value=null selected>Seleziona</option>
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

                        <div class="col-md-6 col-sm-12 col-lg-4">
                            <div class="input-group mb-3">
                                <label class="input-group-text" for="order"><i class="bi bi-building me-2"></i>Commessa</label>
                                <select class="form-select" id="order" name="order">
                                    <option value="">Non presente</option>
                                    @foreach($orders as $order)
                                        <option
                                            value="{{$order->id}}"
                                            class="bg-{{$statuses->where('id',$order->status)->value('color')}} bg-opacity-50">
                                            ({{$order->innerCode}})
                                            - {{$customers->where('id',$order->customer)->value('name')}}
                                            [{{$statuses->where('id',$order->status)->value('description')}}]
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('order')
                            <p class="text-danger fs-6">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="col-md-6 col-sm-12 col-lg-4">
                            <div class="input-group mb-3">
                                <label class="input-group-text" for="customer"><i class="bi bi-building me-2"></i>Cliente</label>
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
                        <div class="col-md-6 col-sm-12 col-lg-4">
                            <div class="input-group mb-3">
                                <label class="input-group-text" for="customer2"><i class="bi bi-building me-2"></i>Cliente
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
                        <div class="col-md-12 col-sm-12 col-lg-6 d-flex justify-content-center mb-3">
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
    <script>
        $(function () {
            $('#contentDetails').addClass("visually-hidden")

            $('#hour_type').on('change', function () {
                switch ($('#hour_type').find(':selected').val()) {
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
        })
    </script>
@endsection
