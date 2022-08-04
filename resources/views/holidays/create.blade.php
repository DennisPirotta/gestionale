@extends('layouts.app')
@php($require_navbar_tools = true)
@section('content')

    <div class="container my-5 shadow-sm p-5">
        <form method="post" action="/ferie" class="row">
            @csrf
            <div class="col-sm-6 col-md-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <span class="input-group-text"><i class="bi bi-clipboard-data me-2"></i>Inizio</span>
                    <input type="date" class="form-control" aria-label="Inizio" name="start">
                </div>
                @error('start')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <span class="input-group-text"><i class="bi bi-clipboard-data me-2"></i>Fine</span>
                    <input type="date" class="form-control" aria-label="Fine" name="end">
                </div>
                @error('end')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <button class="btn btn-primary w-50 mx-auto" type="submit">Salva</button>
        </form>
    </div>

    <div class="container mt-2 mb-0 shadow-sm p-4">
        <div id='calendar'></div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let calendarEl = document.getElementById('calendar')
            let colors = ["rgba(215,239,79,0.84)","#497e29"];
            let borders = ["rgb(250,192,0)","rgb(32,70,15)"];
            let text = ["rgb(0,0,0)","rgb(255,255,255)"];
            let calendar = new FullCalendar.Calendar(calendarEl, {
                contentHeight: 550,
                initialView: 'dayGridMonth',
                themeSystem: 'bootstrap5',
                events: [
                    @foreach($holidays as $event)
                        {
                            'title': "{{DB::table('users')->where('id',$event->user)->value('name')}}",
                            'start': "{{$event->start}}",
                            'end': "{{$event->end}}",
                            'editable': true,
                            'backgroundColor': colors[{{$event->approved}}],
                            'borderColor': borders[{{$event->approved}}],
                            'textColor': text[{{$event->approved}}],
                        },
                    @endforeach
                    ],
                })
            calendar.render()
        })
    </script>
@endsection
