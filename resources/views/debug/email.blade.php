@component('mail::message')
# Bug Report

{{ $object }}
@component('mail::panel')
    {{ $description }}
@endcomponent

Report da,<br>
{{ $sender }}
@endcomponent
