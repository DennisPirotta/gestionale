@component('mail::message')
# Richiesta di ferie

{{ $user }} ha fatto richiesta di @if($approved) un permesso @else ferie @endif @if($start!==$end) da <b>{{ $start }}</b> a <b>{{ $end }}</b> ( incluso ) @else per <b>{{ $start }}</b> @endif

@component('mail::button', ['url' => route('holidays.index') . '#approvare'])
Vai alla pagina di conferma
@endcomponent

Grazie,<br>
{{ config('app.name') }}
@endcomponent