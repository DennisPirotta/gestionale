@extends('layouts.app')
@section('content')
    @if(!session('whereami'))
        {{--
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
        --}}
    @endif

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
        @if(!session('whereami'))
            <div class="alert alert-warning fs-5" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <span>Non hai ancora inserito dove sei, <a href="{{ route('locations.index') }}">inserisci ora</a></span>
            </div>
        @endif
        <div class="row g-3 justify-content-center text-center d-flex">
            <div class="col-xl-3 col-md-4 col-sm-6 mb-3">
                <a href="{{ route('orders.index') }}">
                    <div class="card">
                        <div class="card-body">
                            <i class="bi bi-clipboard-plus fs-1"></i>
                            <h5 class="card-title">Commesse</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-md-4 col-sm-6 mb-3">
                <a href="{{ route('locations.index') }}">
                    <div class="card">
                        <div class="card-body">
                            <i class="bi bi-globe2 fs-1"></i>
                            <h5 class="card-title">Dove Sono</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-md-4 col-sm-6 mb-3">
                <a href="{{ route('hours.index') }}">
                    <div class="card">
                        <div class="card-body">
                            <i class="bi bi-clock-history fs-1"></i>
                            <h5 class="card-title">Gestione ore</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-md-4 col-sm-6 mb-3">
                <a href="{{ route('holidays.index') }}">
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
                <a href="{{ route('customers.index') }}">
                    <div class="card">
                        <div class="card-body">
                            <i class="bi bi-people fs-1"></i>
                            <h5 class="card-title">Clienti</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-md-4 col-sm-6 mb-3">
                <a href="{{ route('orders.report') }}">
                    <div class="card">
                        <div class="card-body">
                            <i class="bi bi-journals fs-1"></i>
                            <h5 class="card-title">Report Commesse</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-md-4 col-sm-6 mb-3">
                <a href="{{ route('users.index') }}">
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
                            <label>
                                <input name="level" type="number" max="2" min="0" class="form-control"/>
                            </label>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success">{{ __('Cambia') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        let deferredPrompt;

        window.addEventListener('beforeinstallprompt', function(event) {
            // Prevent Chrome 67 and earlier from automatically showing the prompt
            event.preventDefault();
            // Stash the event so it can be triggered later.
            deferredPrompt = event;
        });

        /*
        // Installation must be done by a user gesture! Here, the button click
        btnAdd.addEventListener('click', (e) => {
            // hide our user interface that shows our A2HS button
            btnAdd.style.display = 'none';
            // Show the prompt
            deferredPrompt.prompt();
            // Wait for the user to respond to the prompt
            deferredPrompt.userChoice
                .then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('User accepted the A2HS prompt');
                    } else {
                        console.log('User dismissed the A2HS prompt');
                    }
                    deferredPrompt = null;
                });
        });

         */

    </script>
@endsection
