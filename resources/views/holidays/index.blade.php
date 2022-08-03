@extends('layouts.app')
@section('content')

    <div class="container my-3 shadow-sm p-5">
        <div class="row">
            <div class="col-sm-6 col-md-6">
                <div class="bg-opacity-25 bg-success w-100 h-100">
                    {{json_encode($holidays)}}
                    @php($holidays['title'] = "test")
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div id='calendar'></div>
            </div>
        </div>

    </div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let calendarEl = document.getElementById('calendar');
        let calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            themeSystem: 'bootstrap5',
            events: {{$holidays}}
        });
        calendar.render();
    });
</script>
@endsection
