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
                        <option value=null selected>Seleziona la tipologia</option>
                        @foreach($hour_types as $hour_type)
                            <option value="{{$hour_type->id}}">{{$hour_type->description}}</option>
                        @endforeach
                    </select>
                </div>
                @error('company')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class="flex-column d-flex col-12 visually-hidden" id="contentDetails">
                <hr class="mx-auto w-75 my-5">
                <div class="row">
                    <div class="col-md-6 col-sm-12 col-lg-4">
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="hour_type"><i class="bi bi-building me-2"></i>Anno</label>
                            <input type="number"  class="form-control" min="1970" max="2099" step="1" value="{{date('Y')}}" />
                        </div>
                        @error('company')
                        <p class="text-danger fs-6">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="col-md-6 col-sm-12 col-lg-4">
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="hour_type"><i class="bi bi-building me-2"></i>Anno</label>
                            <input type="number"  class="form-control" min="1970" max="2099" step="1" value="{{date('Y')}}" />
                        </div>
                        @error('company')
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
                                        ({{$order->innerCode}}) - {{$customers->where('id',$order->customer)->value('name')}}
                                        [{{$statuses->where('id',$order->status)->value('description')}}]
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('company')
                        <p class="text-danger fs-6">{{$message}}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-12 justify-content-center d-flex">
                <button class="btn btn-primary w-50" type="submit">Salva</button>
            </div>
        </form>
    </div>
    <script>
        $(function (){
            $('#contentDetails').hide()
            $('#hour_type').on('change', function() {
                console.log("change")
                $('#contentDetails').removeClass("visually-hidden")
            });
        })
    </script>
@endsection
