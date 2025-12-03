@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Glamspot')
<img src="https://glamspot.c-automation.cloud/storage/glamspot_footer_logo.png" class="logo" alt="Glamspot">
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
