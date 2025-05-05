@component('mail::message')
{{-- Header --}}
# {{ $subjectLine }}

{{-- Body (HTML allowed) --}}
{!! $body !!}

{{-- Footer --}}
Thanks,<br>
{{ config('app.name') }}
@endcomponent
