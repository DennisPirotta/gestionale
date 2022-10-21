@component('mail::message')
# Ferie Approvate

Ciao {{ $user }}, le tue ferie dal <b>{{ $start }}</b> al <b>{{ $end }}</b> sono state approvate!

Grazie,<br>
{{ config('app.name') }}
@endcomponent
