@extends('layouts.app')
@php($require_navbar_tools = true)
@section('content')
        <div class="container shadow-sm my-5 p-5">
            <div id="calendar"></div>
        </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let calendarEl = document.getElementById('calendar')
            let calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                themeSystem: 'bootstrap5',
                events: "/ferie"
            })
            calendar.render()
        })
    </script>
@endsection
