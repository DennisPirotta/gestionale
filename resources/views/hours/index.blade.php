@extends('layouts.app')
@php($require_navbar_tools = true)
@section('content')
    <div class="container my-3 p-5 shadow-sm">
        <div id="calendar"></div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            let hours = @json($hours, JSON_THROW_ON_ERROR);

            let calendarEl = document.getElementById('calendar')
            let calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    left: 'prev next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridDay,dayGridWeek'
                },
                contentHeight: 550,
                initialView: 'dayGridWeek',
                themeSystem: 'bootstrap5',
                events: hours,
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
