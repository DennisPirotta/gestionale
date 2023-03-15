@component('mail::message')
# Notifica Ferie

Buongiorno, si ricorda che settimana prossima i seguenti dipendenti saranno in ferie

@foreach($holidays as $holiday)
- {{ $holiday->user->name }} {{ $holiday->user->surname }} ( Fino a {{ \Carbon\Carbon::parse($holiday->end)->translatedFormat('l j F Y') }} )
@endforeach

Grazie,<br>
{{ config('app.name') }}
@endcomponent
