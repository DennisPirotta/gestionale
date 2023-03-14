@component('mail::message')
# Notifica Ferie

Buongiorno, si ricorda che domani i seguenti dipendenti saranno in ferie

@foreach($holidays as $holiday)
- {{ $holiday->user->name }} {{ $holiday->user->surname }} ( Fino al {{ \Carbon\Carbon::parse($holiday->end)->format('d-m-Y') }} )
@endforeach

Grazie,<br>
{{ config('app.name') }}
@endcomponent
