
@component('mail::message')
{{-- Greeting --}}
@if (! empty($greeting))
{{ $greeting }}
@else
@if ($level == 'error')
@lang('trax-notification::common.whoops')
@else
@lang('trax-notification::common.hello')
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
    switch ($level) {
        case 'success':
            $color = 'green';
            break;
        case 'error':
            $color = 'red';
            break;
        default:
            $color = 'blue';
    }
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
@lang('trax-notification::common.regards'),<br>@lang('trax-notification::common.author')
@endif

{{-- Subcopy --}}
@isset($actionText)
@component('mail::subcopy')
@lang('trax-notification::common.trouble', ['action' => $actionText]) [{{ $actionUrl }}]({{ $actionUrl }})
@endcomponent
@endisset
@endcomponent
