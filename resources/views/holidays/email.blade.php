@component('mail::message')
# Richiesta di ferie

{{ $user }} ha @if($old_start !== '') modificato la @else fatto @endif richiesta di @if($approved) un permesso @else ferie @endif @if($old_start !== '' ) @if($start!==$end) da <b>{{ $start }}</b> a <b>{{ $end }}</b> ( incluso ) @else per <b>{{ $start }}</b> @endif @endif<br>

@if($old_start !== '')
@component('mail::panel')
Inizio <b>{{ $start }}</b><br>
Fine <b>{{ $end }}</b> ( escluso )
@endcomponent
@component('mail::panel')
Inizio <b>{{ $start }}</b><br>
Fine <b>{{ $end }}</b> ( escluso )
@endcomponent
@endif

@component('mail::button', ['url' => route('holidays.index') . '#approvare'])
Vai alla pagina di conferma
@endcomponent

Grazie,<br>
{{ config('app.name') }}
@endcomponent