@component('mail::message')
{{-- Header --}}
# {{ $subjectLine }}

{{-- Body (HTML allowed) --}}
{!! nl2br(e($body)) !!}

{{-- Footer --}}
Thanks,<br>
{{ config('app.name') }}
@endcomponent
