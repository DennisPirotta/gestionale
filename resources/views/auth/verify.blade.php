@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Verifica il tuo indirizzo Email') }}</div>

                    <div class="card-body">
                        @if (session('resent'))
                            <div class="alert alert-success" role="alert">
                                {{ __('Un nuovo link di verifica è stato inviato alla tua mail.') }}
                            </div>
                        @endif

                        {{ __('Prima di procedere, si prega di controllare la mail per un link di verifica.') }}
                        {{ __('Se non hai ricevuto la mail') }},
                        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                            @csrf
                            <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('clicca qui per richiederne un altra') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
