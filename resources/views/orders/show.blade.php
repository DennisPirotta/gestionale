@php use Carbon\Carbon; @endphp
@extends('layouts.app')
@section('content')
    <div class="container my-5 p-5 text-center shadow-sm">
        <div class="row gy-3">
            <div class="col-sm-6 col-md-3">
                <div class="card h-100 mb-3">
                    <div class="card-body">
                        <i class="bi bi-building fs-3"></i>
                        <p class="card-title">Compagnia</p>
                        <b>{{$commessa->company->name}}</b>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card h-100 mb-3">
                    <div class="card-body">
                        <i class="bi bi-key fs-3"></i>
                        <p class="card-title">Codice Interno</p>
                        <b>{{$commessa->innerCode}}</b>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card h-100 mb-3">
                    <div class="card-body">
                        <i class="bi bi-key fs-3"></i>
                        <p class="card-title">Codice Esterno</p>
                        <b>{{$commessa->outerCode}}</b>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card h-100 mb-3">
                    <div class="card-body">
                        <i class="bi bi-flag fs-3"></i>
                        <p class="card-title">Paese</p>
                        <b>{{$commessa->country->name}}</b>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card h-100 mb-3">
                    <div class="card-body">
                        <i class="bi bi-app-indicator fs-3"></i>
                        <p class="card-title">Stato</p>
                        <b>{{$commessa->status->description}}</b>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card h-100 mb-3">
                    <div class="card-body">
                        <i class="bi bi-journal-text fs-3"></i>
                        <p class="card-title">Descrizione</p>
                        <b>{{$commessa->description}}</b>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card h-100 mb-3">
                    <div class="card-body">
                        <i class="bi bi-battery-charging fs-3"></i>
                        <p class="card-title">Progressi</p>
                        <b>{{$commessa->job_type->description}}</b>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card h-100 mb-3">
                    <div class="card-body">
                        <i class="bi bi-calendar fs-3"></i>
                        <p class="card-title">Apertura</p>
                        <b>{{ Carbon::parse($commessa->opening)->translatedFormat('D d M Y') }}</b>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card h-100 mb-3">
                    <div class="card-body">
                        <i class="bi bi-calendar fs-3"></i>
                        <p class="card-title">Chiusura</p>
                        <b>{{ $commessa->closing !== null ? Carbon::parse($commessa->closing)->translatedFormat('D d M Y') : '/'}}</b>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card h-100 mb-3">
                    <div class="card-body">
                        <i class="bi bi-person fs-3"></i>
                        <p class="card-title">Cliente</p>
                        <b>{{$commessa->customer->name}}</b>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card h-100 mb-3">
                    <div class="card-body">
                        <i class="bi bi-person-check fs-3"></i>
                        <p class="card-title">Responsabile</p>
                        <b>{{$commessa->user->name . " " . $commessa->user->surname}}</b>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card h-100 mb-3">
                    <div class="card-body">
                        <i class="bi bi-clock fs-3"></i>
                        <p class="card-title">Ore SW</p>
                        <b>{{ round($commessa->getHours()['sw'] ?? null) }}</b>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card h-100 mb-3">
                    <div class="card-body">
                        <i class="bi bi-clock fs-3"></i>
                        <p class="card-title">Ore MS</p>
                        <b>{{ round($commessa->getHours()['ms'] ?? null) }}</b>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card h-100 mb-3">
                    <div class="card-body">
                        <i class="bi bi-clock fs-3"></i>
                        <p class="card-title">Ore FAT</p>
                        <b>{{ round($commessa->getHours()['fat'] ?? null) }}</b>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card h-100 mb-3">
                    <div class="card-body">
                        <i class="bi bi-clock fs-3"></i>
                        <p class="card-title">Ore SAF</p>
                        <b>{{ round($commessa->getHours()['saf'] ?? null) }}</b>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3 fs-3">
                <a href="/commesse/{{$commessa->id}}/edit">
                    <div class="card h-100 mb-3">
                        <div class="card-body">
                            <i class="bi bi-pencil-square fs-3"></i>
                            <p class="card-title">Modifica</p>
                        </div>
                    </div>
                </a>
            </div>
            @if(count($commessa->technical_reports)!==0)
                <div class="col-12 table-responsive mt-5">
                    <h2>Fogli intervento</h2>
                    <hr>
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Numero</th>
                            <th scope="col">Operatore</th>
                            <th scope="col">Cliente</th>
                            <th scope="col">Cliente Secondario</th>
                            <th scope="col">Dettagli</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($commessa->technical_reports->sortBy('number') as $report)
                            <tr>
                                <th scope="row">{{ $report->number }}</th>
                                <td>{{ $report->user->name }} {{ $report->user->surname }}</td>
                                <td>{{ $report->customer->name }}</td>
                                <td>{{ $report->secondary_customer->name ?? 'Non presente' }}</td>
                                <td><a href="{{ route('technical_report_details.show',$report->id) }}">Visualizza
                                        dettagli</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
