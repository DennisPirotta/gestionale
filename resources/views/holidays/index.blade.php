@extends('layouts.app')
@php($require_navbar_tools = true)
@section('content')

    @php($used = 0)
    @foreach($holidays as $event)
        @if($event->user == auth()->user()->id)
            @php($used += abs((strtotime($event->end) - strtotime($event->start)) / 86400))
        @endif
    @endforeach
    @php($percentage = (auth()->user()->holidays - $used)*100/auth()->user()->holidays)
    <div class="container mt-3 mb-1 shadow-sm p-5">
        <div class="row text-center">
                <div class="col-sm-6 col-md-6 mb-3">
                    <div class="card"> <!--  bg-black bg-opacity-25 -->
                        <div class="card-body">
                            <i class="bi bi-person fs-3"></i>
                            <span class="card-title fs-3">{{auth()->user()->name}}</span>
                            <hr class="w-50 mx-auto mt-0">
                            <p class="card-text">Giorni di ferie rimasti: <b>{{auth()->user()->holidays - $used}}</b></p>
                            <div class="progress my-3">
                                <div class="progress-bar" role="progressbar" aria-label="Example with label"
                                     style="width: {{$percentage}}%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                    {{$percentage}}% rimasto
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
                                    <button class="btn btn-outline-danger" onclick="return confirm('Sicuro di voler Eliminare?')">
                                        <i class="bi bi-trash me-1"></i>
                                        Elimina
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <div class="col-sm-6 col-md-6">
                <div id='calendar'></div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let calendarEl = document.getElementById('calendar')
            let colors = ["rgba(215,239,79,0.84)","#497e29"];
            let borders = ["rgb(250,192,0)","rgb(32,70,15)"];
            let text = ["rgb(0,0,0)","rgb(255,255,255)"];
            let calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                themeSystem: 'bootstrap5',
                eventSources: [
                    @foreach($holidays as $event)
                    {
                        events: [
                            {
                                'id': "{{$event->id}}",
                                'title': "{{DB::table('users')->where('id',$event->user)->value('name')}}",
                                'start': "{{$event->start}}",
                                'end': "{{$event->end}}",
                                @if($event->user == auth()->user()->id)
                                    'editable': true,
                                @endif
                                'backgroundColor': colors[{{$event->approved}}],
                                'borderColor': borders[{{$event->approved}}],
                                'textColor': text[{{$event->approved}}],
                            },

                        ],

                    },
                    @endforeach
                    ],

                    eventDrop: function (eventInfo){
                        console.log(eventInfo.event.start)
                        const token = document.querySelector('meta[name="csrf-token"]').content;
                        console.log(token)
                        fetch(`/ferie/${eventInfo.event.id}`, {
                            method: 'POST',
                            headers: {
                                "Content-Type": "application/json",
                                "Accept": "application/json, text/plain, */*",
                                "X-Requested-With": "XMLHttpRequest",
                                "X-CSRF-TOKEN": token,
                            },
                            credentials: "same-origin",
                            body: JSON.stringify(
                                {
                                    "_token": token,
                                    "_method": "PUT",
                                    "id": eventInfo.event.id,
                                    "start": eventInfo.event.start.toISOString().split('T')[0],
                                    "end": eventInfo.event.end.toISOString().split('T')[0],
                                    "old_start": eventInfo.oldEvent.start.toISOString().split('T')[0],
                                    "old_end": eventInfo.oldEvent.end.toISOString().split('T')[0],
                                }
                            ),
                        })
                    },

            })
            calendar.render()
        })
    </script>
@endsection
