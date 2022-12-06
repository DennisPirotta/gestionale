@php use Carbon\Carbon; @endphp
@extends('layouts.app')
@section('content')
    <style>
        th:hover{
            cursor: pointer;
        }
    </style>
    <div class="mx-5 shadow-sm my-3 text-center justify-content-center p-3 text-center">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home"
                        type="button" role="tab" aria-controls="nav-home" aria-selected="true">Tabella
                </button>
                <button class="nav-link disabled" id="nav-profile-tab" data-bs-toggle="tab"
                        data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile"
                        aria-selected="false">Grafici
                </button>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active p-3 mt-3" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab"
                 tabindex="0" style="overflow-y: scroll;height: 70vh">
                <table class="table text-center table-bordered">
                    <thead class="align-content-center">
                    <tr>
                        <th scope="col" rowspan="2">#</th>
                        <th scope="col" rowspan="2">Commessa Interna</th>
                        <th scope="col" rowspan="2">Commessa Esterna</th>
                        <th scope="col" rowspan="2">Descrizione</th>
                        <th scope="col" colspan="3">Ore SW</th>
                        <th scope="col" colspan="3">Ore MS</th>
                        <th scope="col" colspan="3">Ore FAT</th>
                        <th scope="col" colspan="3">Ore SAF</th>
                        <th scope="col" rowspan="2">Apertura</th>
                        <th scope="col" rowspan="2">Chiusura</th>
                        <th scope="col" rowspan="2">Cliente</th>
                        <th scope="col" rowspan="2">Responsabile</th>
                        <th scope="col" rowspan="2">Progressi</th>
                    </tr>
                    <tr>
                        <th>Preventivate</th>
                        <th>Usate</th>
                        <th>Residue</th>
                        <th>Preventivate</th>
                        <th>Usate</th>
                        <th>Residue</th>
                        <th>Preventivate</th>
                        <th>Usate</th>
                        <th>Residue</th>
                        <th>Preventivate</th>
                        <th>Usate</th>
                        <th>Residue</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php($count = 1)
                    @foreach($orders as $order)
                        <tr class="table-{{$order->status->color}}">
                            <td><b>{{$count++}}</b></td>
                            <td>{{$order->innerCode}}</td>
                            <td>{{$order->outerCode}}</td>
                            <td>{{$order->description}}</td>

                            <td>{{ round($order->hourSW) }}</td>
                            <td>{{ round($order->getHours()['sw'] ?? null) }}</td>
                            <td>{{ round($order->hourSW) - round($order->getHours()['sw'] ?? null) }}</td>

                            <td>{{ round($order->hourMS) }}</td>
                            <td>{{ round($order->getHours()['ms'] ?? null) }}</td>
                            <td>{{ round($order->hourMS) - round($order->getHours()['ms'] ?? null) }}</td>

                            <td>{{ round($order->hourFAT) }}</td>
                            <td>{{ round($order->getHours()['fat'] ?? null) }}</td>
                            <td>{{ round($order->hourFAT) - round($order->getHours()['fat'] ?? null) }}</td>

                            <td>{{ round($order->hourSAF) }}</td>
                            <td>{{ round($order->getHours()['saf'] ?? null) }}</td>
                            <td>{{ round($order->hourSAF) - round($order->getHours()['saf'] ?? null) }}</td>

                            <td>{{Carbon::parse($order->opening)->format('d-m-Y')}}</td>
                            <td>{{$order->closing !== null ? Carbon::parse($order->closing)->format('d-m-Y') : '/' }}</td>
                            <td>{{$order->customer->name}}</td>
                            <td>{{$order->user->name . " " . $order->user->surname}}</td>
                            <td>{{$order->status->description}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade p-3" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab"
                 tabindex="0">
                <div class="progress mx-auto" data-value='50'>
                    <span class="progress-left">
                        <span class="progress-bar border-primary"></span>
                    </span>
                    <span class="progress-right">
                        <span class="progress-bar border-primary"></span>
                    </span>
                    <div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                        <div class="h2 font-weight-bold">50<sup class="small">%</sup></div>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <script>
        $(function () {

            $(".progress").each(function () {

                let value = $(this).attr('data-value');
                let left = $(this).find('.progress-left .progress-bar');
                let right = $(this).find('.progress-right .progress-bar');

                if (value > 0) {
                    if (value <= 50) {
                        right.css('transform', 'rotate(' + percentageToDegrees(value) + 'deg)')
                    } else {
                        right.css('transform', 'rotate(180deg)')
                        left.css('transform', 'rotate(' + percentageToDegrees(value - 50) + 'deg)')
                    }
                }

            })

            function percentageToDegrees(percentage) {

                return percentage / 100 * 360

            }

        });

        $('th').click(function(){
            let table = $(this).parents('table').eq(0)
            let rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()));
            this.asc = !this.asc
            if (!this.asc){rows = rows.reverse()}
            for (let i = 0; i < rows.length; i++){table.append(rows[i])}
        })
        function comparer(index) {
            return function(a, b) {
                let valA = getCellValue(a, index), valB = getCellValue(b, index);
                return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.toString().localeCompare(valB)
            }
        }
        function getCellValue(row, index){ return $(row).children('td').eq(index).text() }
    </script>

@endsection
