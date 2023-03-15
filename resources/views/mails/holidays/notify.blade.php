@component('mail::message')
# Notifica Ferie

Buongiorno, si ricorda che settimana prossima i seguenti dipendenti saranno in ferie / permesso

@foreach($holidays as $holiday)
    - {{ $holiday->user->name }} {{ $holiday->user->surname }} [@if($holiday->permission) {{ \Carbon\Carbon::parse($holiday->start)->translatedFormat('l j F Y') }} dalle {{ \Carbon\Carbon::parse($holiday->start)->format('H:i') }} alle {{ \Carbon\Carbon::parse($holiday->end)->format('H:i') }} @else Da {{ \Carbon\Carbon::parse($holiday->start)->translatedFormat('l j F Y') }} a {{ \Carbon\Carbon::parse($holiday->end)->translatedFormat('l j F Y') }} @endif]
@endforeach

Grazie,<br>
{{ config('app.name') }}
@endcomponent
