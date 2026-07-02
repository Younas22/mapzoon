@php
    $socials = array_filter([
        'Facebook' => $settings->facebook_url,
        'Twitter' => $settings->twitter_url,
        'Instagram' => $settings->instagram_url,
        'LinkedIn' => $settings->linkedin_url,
        'YouTube' => $settings->youtube_url,
    ]);
@endphp
<tr>
    <td style="background-color:#000000; padding:32px 40px; text-align:center;">
        <p style="margin:0 0 12px; font-family:Arial, Helvetica, sans-serif; font-size:15px; font-weight:700; color:#ffffff; letter-spacing:0.5px;">
            {{ $settings->company_name ?? 'MapZoon' }}
        </p>
        <p style="margin:0 0 16px; font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#9ca3af; line-height:1.6;">
            @if($settings->address)
                {{ $settings->address }}<br>
            @endif
            @if($settings->phone)
                <a href="tel:{{ preg_replace('/\s+/', '', $settings->phone) }}" style="color:#9ca3af; text-decoration:none;">{{ $settings->phone }}</a>
            @endif
            @if($settings->phone && $settings->email)
                &nbsp;&middot;&nbsp;
            @endif
            @if($settings->email)
                <a href="mailto:{{ $settings->email }}" style="color:#9ca3af; text-decoration:none;">{{ $settings->email }}</a>
            @endif
        </p>

        @if(count($socials))
        <p style="margin:0 0 20px; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
            @foreach($socials as $label => $url)
                <a href="{{ $url }}" style="color:#00bf63; text-decoration:none; font-weight:700; margin:0 8px;">{{ $label }}</a>@if(!$loop->last)<span style="color:#374151;">&bull;</span>@endif
            @endforeach
        </p>
        @endif

        <p style="margin:0; font-family:Arial, Helvetica, sans-serif; font-size:11px; color:#6b7280;">
            &copy; {{ date('Y') }} {{ $settings->company_name ?? 'MapZoon' }}. All rights reserved.
        </p>
    </td>
</tr>
