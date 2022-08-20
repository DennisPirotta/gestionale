@extends('layouts.app')
@section('content')
    <div class="container-fluid w-75 shadow-sm my-3 text-center justify-content-center p-3 text-center">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Tabella</button>
                <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Grafici</button>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active p-3" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0" style="height: 70vh">
                <table class="table pt-3" style="overflow-y: scroll;">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Commessa Interna</th>
                            <th scope="col">Commessa Esterna</th>
                            <th scope="col">Descrizione</th>
                            <th scope="col">Ore SW</th>
                            <th scope="col">Ore MS</th>
                            <th scope="col">Ore FAT</th>
                            <th scope="col">Ore SAF</th>
                            <th scope="col">Apertura</th>
                            <th scope="col">Chiusura</th>
                            <th scope="col">Cliente</th>
                            <th scope="col">Responsabile</th>
                            <th scope="col">Progressi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @php($count = 1)
                    @foreach($orders as $order)
                        <tr class="table-{{$order->status->color}}">
                            <th scope="row">{{$count++}}</th>
                            <td>{{$order->innerCode}}</td>
                            <td>{{$order->outerCode}}</td>
                            <td>{{$order->description}}</td>
                            <td>{{$order->hourSW}}</td>
                            <td>{{$order->hourMS}}</td>
                            <td>{{$order->hourFAT}}</td>
                            <td>{{$order->hourSAF}}</td>
                            <td>{{$order->opening}}</td>
                            <td>{{$order->closing}}</td>
                            <td>{{$order->customer->name}}</td>
                            <td>{{$order->user->name}}</td>
                            <td>{{$order->status->description}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade p-3" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">
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
    </script>

@endsection
