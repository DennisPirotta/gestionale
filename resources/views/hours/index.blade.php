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
                nowIndicator: true,
                slotDuration: '1:00',
                slotMinTime: '8:00',
                slotMaxTime: '18:00',
                selectable: true,
                editable: true,
                allDaySlot: true,
                initialView: 'dayGridWeek',
                themeSystem: 'bootstrap5',
                events: hours,
                select: function (info){
                    console.log(info)
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
