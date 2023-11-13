@php use Carbon\Carbon; use App\Models\JobType @endphp
@extends('layouts.app')
@section('content')
    <style>
        th:hover{
            cursor: pointer;
        }
    </style>
    <div class="mx-3 shadow-sm my-3 text-center justify-content-center p-3 text-center">
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
                    <thead headers="sticky">
                    <tr class="head">
                        <th scope="col" rowspan="2">#</th>
                        <th scope="col" rowspan="2">Ditta</th>
                        <th scope="col" rowspan="2">Commessa Interna</th>
                        <th scope="col" rowspan="2">Commessa Esterna</th>
                        <th scope="col" rowspan="2">Descrizione</th>
                        <th scope="col" colspan="3">Ore SW</th>
                        <th scope="col" rowspan="2">Fatturata</th>
                        <th scope="col" rowspan="2">Ore Modifiche</th>
                        <th scope="col" colspan="3">Ore MS</th>
                        <th scope="col" colspan="3">Ore FAT</th>
                        <th scope="col" colspan="3">Ore SAF</th>
                        <th scope="col" rowspan="2">Apertura</th>
                        <th scope="col" rowspan="2">Chiusura</th>
                        <th scope="col" rowspan="2">Cliente</th>
                        <th scope="col" rowspan="2">Responsabile</th>
                        <th scope="col" rowspan="2">Progressi</th>
                    </tr>
                    <tr class="head">
                        <th scope="col">Preventivate</th>
                        <th scope="col">Usate</th>
                        <th scope="col">Residue</th>
                        <th scope="col">Preventivate</th>
                        <th scope="col">Usate</th>
                        <th scope="col">Residue</th>
                        <th scope="col">Preventivate</th>
                        <th scope="col">Usate</th>
                        <th scope="col">Residue</th>
                        <th scope="col">Preventivate</th>
                        <th scope="col">Usate</th>
                        <th scope="col">Residue</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php($count = 1)
                    @foreach($orders as $order)
                        <tr class="table-{{$order->status->color}}">
                            <td><b>{{$count++}}</b></td>
                            <td>{{ $order->company->name }}</td>
                            <td>{{$order->innerCode}}</td>
                            <td>{{$order->outerCode}}</td>
                            <td>{{$order->description}}</td>

                            <td>{{ $order->hourSW }}</td>
                            <td>
                                {{ $order->getHours(JobType::SVILUPPO_SOFTWARE)->get('count',0) }}
                                <br>
                                @if(count($order->getHours(JobType::SVILUPPO_SOFTWARE)) > 1)
                                    @php($sw = '')
                                    @foreach($order->getHours(JobType::SVILUPPO_SOFTWARE)->forget('count') as $user=>$hour)
                                        @php($sw .= '<b>'.$user.'</b> : '.$hour.'<br>')
                                    @endforeach
                                    <a tabindex="0" class="bi bi-info-circle text-primary" data-bs-trigger="focus" data-bs-toggle="popover" data-bs-title="Dettagli" data-bs-content="{{ $sw }}"></a>
                                    @if($order->hourSW > 0)
                                        @if((($order->getHours(JobType::SVILUPPO_SOFTWARE)['count'] ?? 0) * 100) / $order->hourSW > 100)
                                            <a tabindex="0" class="bi bi-exclamation-triangle" style="color: #dc3545" data-bs-trigger="hover" data-bs-toggle="popover" data-bs-title="Avviso" data-bs-content="Sono state utilizzate più del 100% delle ore previste, [ {{ round((($order->getHours(JobType::SVILUPPO_SOFTWARE)['count'] ?? 0) * 100) / $order->hourSW,1) }}% ]"></a>
                                        @elseif((($order->getHours(JobType::SVILUPPO_SOFTWARE)['count'] ?? 0) * 100) / $order->hourSW > 75)
                                            <a tabindex="0" class="bi bi-exclamation-triangle" style="color: #fd7e14" data-bs-trigger="hover" data-bs-toggle="popover" data-bs-title="Avviso" data-bs-content="Sono state utilizzate più del 75% delle ore previste, [ {{ round((($order->getHours(JobType::SVILUPPO_SOFTWARE)['count'] ?? 0) * 100) / $order->hourSW,1) }}% ]"></a>
                                        @elseif((($order->getHours(JobType::SVILUPPO_SOFTWARE)['count'] ?? 0) * 100) / $order->hourSW > 50)
                                            <a tabindex="0" class="bi bi-exclamation-triangle" style="color: #ffc107" data-bs-trigger="hover" data-bs-toggle="popover" data-bs-title="Avviso" data-bs-content="Sono state utilizzate più del 50% delle ore previste, [ {{ round((($order->getHours(JobType::SVILUPPO_SOFTWARE)['count'] ?? 0) * 100) / $order->hourSW,1) }}% ]"></a>
                                        @endif
                                    @endif
                                @endif
                            </td>
                            <td>{{ $order->hourSW - $order->getHours(JobType::SVILUPPO_SOFTWARE)['count'] ?? 0 }}</td>
                            <td>
                                @if($order->invoiced)
                                    <i class="bi bi-check-circle text-success fs-5" data-bs-toggle="modal" data-bs-target="#invoiceModal" onclick="selectOrder({{$order->id}},{{ $order->invoiced }})"></i>
                                @else
                                    <i class="bi bi-x-circle text-danger fs-5" data-bs-toggle="modal" data-bs-target="#invoiceModal" onclick="selectOrder({{$order->id}},{{ $order->invoiced }})"></i>
                                @endif
                            </td>
                            <td>
                                {{$order->getHours(JobType::MODIFICHE)['count'] ?? 0}}
                                @if(count($order->getHours(JobType::MODIFICHE)) > 1)
                                    @php($mod = '')
                                    @foreach($order->getHours(JobType::MODIFICHE)->forget('count') as $user=>$hour)
                                        @php($mod .= '<b>'.$user.'</b> : '.$hour.'<br>')
                                    @endforeach
                                    <a tabindex="0" class="bi bi-info-circle text-primary" data-bs-trigger="focus" data-bs-toggle="popover" data-bs-title="Dettagli" data-bs-content="{{ $mod }}"></a>
                                @endif
                            </td>

                            <td>{{ $order->hourMS }}</td>
                            <td>
                                {{ $order->getHours(JobType::MESSA_IN_SERVIZIO)->get('count',0) }}
                                <br>
                                @if(count($order->getHours(JobType::MESSA_IN_SERVIZIO)) > 1)
                                    @php($ms = '')
                                    @foreach($order->getHours(JobType::MESSA_IN_SERVIZIO)->forget('count') as $user=>$hour)
                                        @php($ms .= '<b>'.$user.'</b> : '.$hour.'<br>')
                                    @endforeach
                                    <a tabindex="0" class="bi bi-info-circle text-primary" data-bs-trigger="focus" data-bs-toggle="popover" data-bs-title="Dettagli" data-bs-content="{{ $ms }}"></a>
                                    @if($order->hourMS > 0)
                                        @if((($order->getHours(JobType::MESSA_IN_SERVIZIO)['count'] ?? 0) * 100) / $order->hourMS > 100)
                                            <a tabindex="0" class="bi bi-exclamation-triangle" style="color: #dc3545" data-bs-trigger="hover" data-bs-toggle="popover" data-bs-title="Avviso" data-bs-content="Sono state utilizzate più del 100% delle ore previste, [ {{ round((($order->getHours(JobType::MESSA_IN_SERVIZIO)['count'] ?? 0) * 100) / $order->hourMS,1) }}% ]"></a>
                                        @elseif((($order->getHours(JobType::MESSA_IN_SERVIZIO)['count'] ?? 0) * 100) / $order->hourMS > 75)
                                            <a tabindex="0" class="bi bi-exclamation-triangle" style="color: #fd7e14" data-bs-trigger="hover" data-bs-toggle="popover" data-bs-title="Avviso" data-bs-content="Sono state utilizzate più del 75% delle ore previste, [ {{ round((($order->getHours(JobType::MESSA_IN_SERVIZIO)['count'] ?? 0) * 100) / $order->hourMS,1) }}% ]"></a>
                                        @elseif((($order->getHours(JobType::MESSA_IN_SERVIZIO)['count'] ?? 0) * 100) / $order->hourMS > 50)
                                            <a tabindex="0" class="bi bi-exclamation-triangle" style="color: #ffc107" data-bs-trigger="hover" data-bs-toggle="popover" data-bs-title="Avviso" data-bs-content="Sono state utilizzate più del 50% delle ore previste, [ {{ round((($order->getHours(JobType::MESSA_IN_SERVIZIO)['count'] ?? 0) * 100) / $order->hourMS,1) }}% ]"></a>
                                        @endif
                                    @endif
                                @endif
                            </td>
                            <td>{{ $order->hourMS - $order->getHours(JobType::MESSA_IN_SERVIZIO)['count'] ?? 0 }}</td>

                            <td>{{ $order->hourFAT }}</td>
                            <td>
                                {{ $order->getHours(JobType::COLLAUDO)->get('count',0) }}
                                <br>
                                @if(count($order->getHours(JobType::COLLAUDO)) > 1)
                                    @php($fat = '')
                                    @foreach($order->getHours(JobType::COLLAUDO)->forget('count') as $user=>$hour)
                                        @php($fat .= '<b>'.$user.'</b> : '.$hour.'<br>')
                                    @endforeach
                                    <a tabindex="0" class="bi bi-info-circle text-primary" data-bs-trigger="focus" data-bs-toggle="popover" data-bs-title="Dettagli" data-bs-content="{{ $fat }}"></a>
                                    @if($order->hourFAT > 0)
                                        @if((($order->getHours(JobType::COLLAUDO)['count'] ?? 0) * 100) / $order->hourFAT > 100)
                                            <a tabindex="0" class="bi bi-exclamation-triangle" style="color: #dc3545" data-bs-trigger="hover" data-bs-toggle="popover" data-bs-title="Avviso" data-bs-content="Sono state utilizzate più del 100% delle ore previste, [ {{ round((($order->getHours(JobType::COLLAUDO)['count'] ?? 0) * 100) / $order->hourFAT,1) }}% ]"></a>
                                        @elseif((($order->getHours(JobType::COLLAUDO)['count'] ?? 0) * 100) / $order->hourFAT > 75)
                                            <a tabindex="0" class="bi bi-exclamation-triangle" style="color: #fd7e14" data-bs-trigger="hover" data-bs-toggle="popover" data-bs-title="Avviso" data-bs-content="Sono state utilizzate più del 75% delle ore previste, [ {{ round((($order->getHours(JobType::COLLAUDO)['count'] ?? 0) * 100) / $order->hourFAT,1) }}% ]"></a>
                                        @elseif((($order->getHours(JobType::COLLAUDO)['count'] ?? 0) * 100) / $order->hourFAT > 50)
                                            <a tabindex="0" class="bi bi-exclamation-triangle" style="color: #ffc107" data-bs-trigger="hover" data-bs-toggle="popover" data-bs-title="Avviso" data-bs-content="Sono state utilizzate più del 50% delle ore previste, [ {{ round((($order->getHours(JobType::COLLAUDO)['count'] ?? 0) * 100) / $order->hourFAT,1) }}% ]"></a>                                        @endif
                                    @endif
                                @endif
                            </td>
                            <td>{{ $order->hourFAT - $order->getHours(JobType::COLLAUDO)['count'] ?? 0 }}</td>

                            <td>{{ $order->hourSAF }}</td>
                            <td>
                                {{ $order->getHours(JobType::SAFETY)->get('count',0) }}
                                <br>
                                @if(count($order->getHours(JobType::SAFETY)) > 1)
                                    @php($saf = '')
                                    @foreach($order->getHours(JobType::SAFETY)->forget('count') as $user=>$hour)
                                        @php($saf .= '<b>'.$user.'</b> : '.$hour.'<br>')
                                    @endforeach
                                    <a tabindex="0" class="bi bi-info-circle text-primary" data-bs-trigger="focus" data-bs-toggle="popover" data-bs-title="Dettagli" data-bs-content="{{ $saf }}"></a>
                                    @if($order->hourSAF > 0)
                                        @if((($order->getHours(JobType::SAFETY)['count'] ?? 0) * 100) / $order->hourSAF > 100)
                                            <a tabindex="0" class="bi bi-exclamation-triangle" style="color: #dc3545" data-bs-trigger="hover" data-bs-toggle="popover" data-bs-title="Avviso" data-bs-content="Sono state utilizzate più del 100% delle ore previste, [ {{ round((($order->getHours(JobType::SAFETY)['count'] ?? 0) * 100) / $order->hourSAF,1) }}% ]"></a>
                                        @elseif((($order->getHours(JobType::SAFETY)['count'] ?? 0) * 100) / $order->hourSAF > 75)
                                            <a tabindex="0" class="bi bi-exclamation-triangle" style="color: #fd7e14" data-bs-trigger="hover" data-bs-toggle="popover" data-bs-title="Avviso" data-bs-content="Sono state utilizzate più del 75% delle ore previste, [ {{ round((($order->getHours(JobType::SAFETY)['count'] ?? 0) * 100) / $order->hourSAF,1) }}% ]"></a>
                                        @elseif((($order->getHours(JobType::SAFETY)['count'] ?? 0) * 100) / $order->hourSAF > 50)
                                            <a tabindex="0" class="bi bi-exclamation-triangle" style="color: #ffc107" data-bs-trigger="hover" data-bs-toggle="popover" data-bs-title="Avviso" data-bs-content="Sono state utilizzate più del 50% delle ore previste, [ {{ round((($order->getHours(JobType::SAFETY)['count'] ?? 0) * 100) / $order->hourSAF,1) }}% ]"></a>
                                        @endif
                                    @endif
                                @endif
                            </td>
                            <td>{{ $order->hourSAF - $order->getHours(JobType::SAFETY)['count'] ?? 0 }}</td>

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
        function selectOrder(order_id,current_status)
        {
            $('#id_order_invoice').val(order_id)
            $('#status_checkbox').prop('checked',current_status)
        }
        $(function () {
            const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
            const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl,{ html:true }))

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

    <!-- Modal -->
    <div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Commessa Fatturata?</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('orders.updateInvoice')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="status_checkbox" name="status">
                            <input type="hidden" id="id_order_invoice" name="order_id">
                            <label class="form-check-label" for="status_checkbox">
                                Spunta questa casella se la commessa è stata fatturata
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                        <button type="submit" class="btn btn-primary">Salva</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
