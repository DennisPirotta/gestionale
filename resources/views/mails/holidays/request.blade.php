@component('mail::message')
# Richiesta di ferie

{{ $holiday->user->name }} {{ $holiday->user->surname }} ha fatto richiesta di @if($holiday->permission) un permesso il giorno {{ Carbon\Carbon::parse($holiday->start)->translatedFormat('l j F Y') }} dalle {{ Carbon\Carbon::parse($holiday->start)->translatedFormat('H:i') }} alle {{ Carbon\Carbon::parse($holiday->end)->translatedFormat('H:i') }}  @else ferie da {{ \Carbon\Carbon::parse($holiday->start)->translatedFormat('l j F Y') }} (incluso) a {{ \Carbon\Carbon::parse($holiday->end)->translatedFormat('l j F Y') }} (escluso) @endif

{{--@if($old_start !== '')--}}
{{--@component('mail::panel')--}}
{{--# Vecchia richiesta--}}
{{--Inizio <b>{{ $old_start }}</b><br>--}}
{{--Fine <b>{{ $old_end }}</b> ( escluso )--}}
{{--@endcomponent--}}
{{--@component('mail::panel')--}}
{{--# Nuova richiesta--}}
{{--Inizio <b>{{ $start }}</b><br>--}}
{{--Fine <b>{{ $end }}</b> ( escluso )--}}
{{--@endcomponent--}}
{{--@endif--}}

@component('mail::button', ['url' => route('holidays.index') . '#approvare'])
Vai alla pagina di conferma
@endcomponent

Grazie,<br>
{{ config('app.name') }}
@endcomponent
