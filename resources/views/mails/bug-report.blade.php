@component('mail::message')
# Richiesta Ferie

{{ $object }}
@component('mail::panel')
    {{ $description }}
@endcomponent

Report da,<br>
{{ $sender }}
@endcomponent
