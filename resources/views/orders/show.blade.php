@extends('layouts.app')
@section('content')
<div class="container my-5 p-5 text-center shadow-sm">
    <div class="row gy-3">
        <div class="col-sm-6 col-md-3">
            <div class="card h-100 mb-3">
                <div class="card-body">
                    <i class="bi bi-building fs-3"></i>
                    <p class="card-title">Compagnia</p>
                    <b>{{DB::table('companies')->where('id',$commessa['company'])->value('name')}}</b>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card h-100 mb-3">
                <div class="card-body">
                    <i class="bi bi-key fs-3"></i>
                    <p class="card-title">Codice Interno</p>
                    <b>{{$commessa['innerCode']}}</b>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card h-100 mb-3">
                <div class="card-body">
                    <i class="bi bi-key fs-3"></i>
                    <p class="card-title">Codice Esterno</p>
                    <b>{{$commessa['outerCode']}}</b>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card h-100 mb-3">
                <div class="card-body">
                    <i class="bi bi-flag fs-3"></i>
                    <p class="card-title">Paese</p>
                    <b>{{DB::table('countries')->where('id',$commessa['country'])->value('name')}}</b>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card h-100 mb-3">
                <div class="card-body">
                    <i class="bi bi-app-indicator fs-3"></i>
                    <p class="card-title">Stato</p>
                    <b>{{DB::table('statuses')->where('id',$commessa['status'])->value('description')}}</b>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card h-100 mb-3">
                <div class="card-body">
                    <i class="bi bi-journal-text fs-3"></i>
                    <p class="card-title">Descrizione</p>
                    <b>{{$commessa['description']}}</b>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card h-100 mb-3">
                <div class="card-body">
                    <i class="bi bi-battery-charging fs-3"></i>
                    <p class="card-title">Progressi</p>
                    <b>{{$commessa['progress']}}</b>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card h-100 mb-3">
                <div class="card-body">
                    <i class="bi bi-calendar fs-3"></i>
                    <p class="card-title">Apertura</p>
                    <b>{{$commessa['opening']}}</b>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card h-100 mb-3">
                <div class="card-body">
                    <i class="bi bi-calendar fs-3"></i>
                    <p class="card-title">Chiusura</p>
                    <b>{{$commessa['closing']}}</b>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card h-100 mb-3">
                <div class="card-body">
                    <i class="bi bi-person fs-3"></i>
                    <p class="card-title">Cliente</p>
                    <b>{{DB::table('customers')->where('id',$commessa['customer'])->value('name')}}</b>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card h-100 mb-3">
                <div class="card-body">
                    <i class="bi bi-person-check fs-3"></i>
                    <p class="card-title">Responsabile</p>
                    <b>{{DB::table('employees')->where('id',$commessa['manager'])->value('name')}}</b>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card h-100 mb-3">
                <div class="card-body">
                    <i class="bi bi-clock fs-3"></i>
                    <p class="card-title">Ore SW</p>
                    <b>{{$commessa['hourSW']}}</b>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card h-100 mb-3">
                <div class="card-body">
                    <i class="bi bi-clock fs-3"></i>
                    <p class="card-title">Ore MS</p>
                    <b>{{$commessa['hourMS']}}</b>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card h-100 mb-3">
                <div class="card-body">
                    <i class="bi bi-clock fs-3"></i>
                    <p class="card-title">Ore FAT</p>
                    <b>{{$commessa['hourFAT']}}</b>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card h-100 mb-3">
                <div class="card-body">
                    <i class="bi bi-clock fs-3"></i>
                    <p class="card-title">Ore SAF</p>
                    <b>{{$commessa['hourSAF']}}</b>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3 fs-3">
            <a href="/commesse/{{$commessa['id']}}/edit">
                <div class="card h-100 mb-3">
                    <div class="card-body">
                        <i class="bi bi-pencil-square fs-3"></i>
                        <p class="card-title">Modifica</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
