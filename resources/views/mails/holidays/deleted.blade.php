@component('mail::message')
# Ferie Eliminate

{{ $holiday->user->name }} {{ $holiday->user->surname }} ha eliminato la richiesta di @if($holiday->permission) un permesso il giorno {{ Carbon\Carbon::parse($holiday->start)->translatedFormat('l j F Y') }} dalle {{ Carbon\Carbon::parse($holiday->start)->translatedFormat('H:i') }} alle {{ Carbon\Carbon::parse($holiday->end)->translatedFormat('H:i') }} @else ferie da <b>{{ Carbon\Carbon::parse($holiday->start)->translatedFormat('l j F Y') }}</b> a <b>{{ Carbon\Carbon::parse($holiday->end)->translatedFormat('l j F Y') }}</b> @endif

<br>
{{ config('app.name') }}
@endcomponent
