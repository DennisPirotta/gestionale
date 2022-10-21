@component('mail::message')
# Ferie Eliminate

{{ $user }} ha eliminato la richiesta di ferie dal <b>{{ $start }}</b> al <b>{{ $end }}</b>

Grazie,<br>
{{ config('app.name') }}
@endcomponent
