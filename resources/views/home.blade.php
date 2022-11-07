@extends('layouts.app')
@section('content')
    @if(auth()->user()->first_login)
        <div class="modal modal-xl fade" id="first_login" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header justify-content-center">
                        <h5 class="modal-title fs-3" id="whereami">Benvenuto</h5>
                    </div>
                        <div class="modal-body fs-5">
                            <div class="row align-items-center">
                                <div class="col px-5">
                                    <div class="alert alert-success">
                                        <i class="bi bi-check2-circle fs-4 me-2"></i>
                                        Benvenuto nel nuovo sistema <b>gestionale</b>
                                    </div>
                                    <div class="alert alert-warning">
                                        <i class="bi bi-exclamation-triangle fs-4 me-2"></i>
                                        In caso dovessi riscontare <b>bug</b> o <b>malfunzionamenti</b> segnalali nell'apposita sezione di <a href="/bug-report" class="text-decoration-underline">Bug Report</a>
                                    </div>
                                </div>
                                <div class="col pe-5">
                                    <lottie-player src="https://assets7.lottiefiles.com/datafiles/e5MJRz6ayDOFFTT/data.json"  background="transparent"  speed="1" loop autoplay></lottie-player>
                                </div>
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
        @if(session()->has('whereami'))
            <div class="alert alert-warning fs-5" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <span>Non hai ancora inserito dove sei, <a href="{{ route('locations.index') }}">inserisci ora</a></span>
            </div>
        @endif
        <div class="row g-3 justify-content-center text-center d-flex">

            <x-home-card :redirect="route('orders.index')" :icon="'bi-clipboard-plus'" :title="'Commesse'"></x-home-card>
            <x-home-card :redirect="route('locations.index')" :icon="'bi-globe2'" :title="'Dove Sono'"></x-home-card>
            <x-home-card :redirect="route('hours.index')" :icon="'bi-clock-history'" :title="'Gestione Ore'"></x-home-card>
            <x-home-card :redirect="route('holidays.index')" :icon="'bi-calendar4-week'" :title="'Ferie'"></x-home-card>
            @hasanyrole('boss|admin')
            <x-home-card :redirect="route('customers.index')" :icon="'bi-people'" :title="'Clienti'"></x-home-card>
            <x-home-card :redirect="route('orders.report')" :icon="'bi-journals'" :title="'Report Commesse'"></x-home-card>
            <x-home-card :redirect="route('users.index')" :icon="'bi-person-workspace'" :title="'Gestione Dipendenti'"></x-home-card>
            <x-home-card :redirect="route('hours.report')" :icon="'bi-hourglass-split'" :title="'Report ore'"></x-home-card>
            @endhasanyrole

            @role('boss')
                <x-home-card :redirect="route('engagement.index')" :icon="'bi-calendar2-check'" :title="'Impegni'"></x-home-card>
            @endrole

            @if(env('APP_DEBUG') && auth()->user()->hasRole('developer'))
                <div class="col-12 mb-3">
                    <form method="post" action="/debug/change_permissions">
                        @csrf
                        <div class="card w-50 mx-auto">
                            <div class="card-body">
                                <i class="bi bi-bug fs-1"></i>
                                <h5 class="card-title">Debug</h5>
                                <select name="role" class="form-select w-50 mx-auto">
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
                                    <option value="boss">Boss</option>
                                    <option value="developer">Developer</option>
                                </select>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success">{{ __('Cambia') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
   <script>
        let deferredPrompt;
        window.addEventListener('beforeinstallprompt', function(event) {
            event.preventDefault();
            deferredPrompt = event;
        });

        {{--

        let btnAdd = document.getElementById('install')
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
                        btnAdd.style.display = 'block';
                    }
                    deferredPrompt = null;
                });
        });

        --}}
    </script>
@endsection
