@extends('layouts.app')
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-business-days/1.2.0/index.js" integrity="sha512-SOgJ28iBwBLZWvQUbMVFcvdGOTCDVmaSshVd+e/o60PDJMQB1TcMSVV0sh2ZJqp2rmZt+I2ir+VMIPY8Ng172A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <div class="container my-5 p-5">
        <div id="calendar"></div>
    </div>

    <script type="module">
        console.log(window.moment().format())
        console.log(window.business.isWeekDay(moment()))
    </script>
    <script>

        $(function(){
            let el = document.getElementById('calendar')
            let calendar = new FullCalendar.Calendar(el,{
                initialView: 'dayGridMonth',
                themeSystem: 'bootstrap5',
                businessHours: {
                    daysOfWeek: [ 1, 2, 3, 4 ,5 ], // Lunedì - Venerdì
                    startTime: '8:00',
                    endTime: '17:00',
                },
                headerToolbar: {
                    left: 'prev next today',
                    center: 'title',
                    right: 'listWeek timeGridWeek dayGridMonth'
                },
            })
            calendar.render()
        })
    </script>
    @endsection
