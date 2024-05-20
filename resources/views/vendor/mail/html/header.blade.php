<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            @if (trim($slot) === 'Laravel')
            <img src="{{ asset('assets/images/logo/logo.svg') }}" class="logo" alt="{{ env('APP_NAME') }} Logo">
            @else
            {{ $slot }}
            @endif
        </a>
    </td>
</tr>