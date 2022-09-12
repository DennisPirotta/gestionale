@extends('layouts.app')
@section('content')
    @if(!session('whereami'))
        <div class="modal fade" id="whereami" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="whereami">Dove sei</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="/whereami" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1"><i class="bi bi-geo-alt"></i></span>
                                <input type="text" class="form-control" name="whereami" placeholder="Dove sei?" aria-label="Dove sono" aria-describedby="basic-addon1" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancella</button>
                            <button type="submit" class="btn btn-primary">Salva</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <script>
            $(() => $('#whereami').modal('toggle'))
        </script>
    @endif
    {{--@if()--}}
    @if(auth()->user()->first_login)
        <div class="modal modal-xl fade" id="first_login" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header justify-content-center">
                        <h5 class="modal-title" id="whereami">Benvenuto</h5>
                    </div>
                        <div class="modal-body fs-5">
                            <div class="alert alert-success">
                                <i class="bi bi-check2-circle fs-4 me-2"></i>
                                Benvenuto nel nuovo sistema <b>gestionale</b>
                            </div>
                            <div class="alert alert-primary">
                                <i class="bi bi-info-circle fs-4 me-2"></i>
                                Per ottenere informazioni circa l'utilizzo consulta <a href="/manuale" class="text-decoration-underline">il manuale d'uso</a>
                            </div>
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle fs-4 me-2"></i>
                                In caso dovessi riscontare <b>bug</b> o <b>malfunzionamenti</b> segnalali nell'apposita sezione di <a href="/bug-report" class="text-decoration-underline">Bug Report</a>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <form method="post" action="/" class="w-100 justify-content-center d-flex">
                                @csrf
                                <button type="submit" class="btn btn-success w-50" data-bs-dismiss="modal">Inizia</button>
                            </form>
                        </div>
                </div>
            </div>
        </div>
        <script>
            $(() => {
                new bootstrap.Modal('#first_login',{
                    focus: true,
                    backdrop: 'static',
                    keyboard: false
                }).show()
            })
        </script>
    @endif
    <div class="container my-5 p-3">
        <div class="row g-3 justify-content-center text-center d-flex">
            <div class="col-xl-3 col-md-4 col-sm-6 mb-3">
                <a href="{{url('/commesse')}}">
                    <div class="card">
                        <div class="card-body">
                            <i class="bi bi-clipboard-plus fs-1"></i>
                            <h5 class="card-title">Commesse</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-md-4 col-sm-6 mb-3">
                <a href="{{url('/dove_siamo')}}">
                    <div class="card">
                        <div class="card-body">
                            <i class="bi bi-globe2 fs-1"></i>
                            <h5 class="card-title">Dove Sono</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-md-4 col-sm-6 mb-3">
                <a href="{{url('/ore')}}">
                    <div class="card">
                        <div class="card-body">
                            <i class="bi bi-clock-history fs-1"></i>
                            <h5 class="card-title">Gestione ore</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-md-4 col-sm-6 mb-3">
                <a href="{{url('/ferie')}}">
                    <div class="card">
                        <div class="card-body">
                            <i class="bi bi-calendar4-week fs-1"></i>
                            <h5 class="card-title">Ferie</h5>
                        </div>
                    </div>
                </a>
            </div>
            @if(auth()->user()->level > 0)
                <div class="col-12">
                    <hr class="my-3 w-75 mx-auto">
                </div>

            <div class="col-xl-3 col-md-4 col-sm-6 mb-3">
                <a href="{{url('/clienti')}}">
                    <div class="card">
                        <div class="card-body">
                            <i class="bi bi-people fs-1"></i>
                            <h5 class="card-title">Clienti</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-md-4 col-sm-6 mb-3">
                <a href="{{url('/commesse/report')}}">
                    <div class="card">
                        <div class="card-body">
                            <i class="bi bi-journals fs-1"></i>
                            <h5 class="card-title">Report Commesse</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-md-4 col-sm-6 mb-3">
                <a href="{{url('/dipendenti')}}">
                    <div class="card">
                        <div class="card-body">
                            <i class="bi bi-person-workspace fs-1"></i>
                            <h5 class="card-title">Gestione Dipendenti</h5>
                        </div>
                    </div>
                </a>
            </div>
            @endif
            <div class="col-12 mb-3">
                <form method="post" action="/debug/change_permissions">
                    @csrf
                    <div class="card w-50 mx-auto">
                        <div class="card-body">
                            <i class="bi bi-bug fs-1"></i>
                            <h5 class="card-title">Debug</h5>
                            <input name="level" type="number" max="2" min="0" class="form-control"/>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success">{{ __('Cambia') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
