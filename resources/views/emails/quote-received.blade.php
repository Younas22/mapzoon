<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $settings->company_name ?? 'MapZoon' }}</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f5; font-family:Arial, Helvetica, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f5; padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px; width:100%; background-color:#ffffff; border-radius:12px; overflow:hidden;">

                    @include('emails.partials.header')

                    <tr>
                        <td style="padding:40px;">
                            <p style="margin:0 0 4px; font-family:Arial, Helvetica, sans-serif; font-size:13px; font-weight:700; color:#00bf63; text-transform:uppercase; letter-spacing:1px;">
                                Quote Request Received
                            </p>
                            <h1 style="margin:0 0 20px; font-family:Arial, Helvetica, sans-serif; font-size:22px; color:#0b0b0b;">
                                Hi {{ explode(' ', trim($lead->name))[0] ?: 'there' }}, thanks for reaching out!
                            </h1>
                            <p style="margin:0 0 24px; font-family:Arial, Helvetica, sans-serif; font-size:15px; line-height:1.7; color:#374151;">
                                We've received your request for a free quote. Our team is reviewing the details you shared and will get back to you within <strong>24 hours</strong>.
                            </p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f9fafb; border:1px solid #e5e7eb; border-radius:10px; margin-bottom:28px;">
                                <tr>
                                    <td style="padding:20px 24px;">
                                        <p style="margin:0 0 12px; font-family:Arial, Helvetica, sans-serif; font-size:13px; font-weight:700; color:#0b0b0b; text-transform:uppercase; letter-spacing:0.5px;">
                                            Your Submission
                                        </p>
                                        @if($lead->service)
                                        <p style="margin:0 0 8px; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#374151;"><strong style="color:#0b0b0b;">Service:</strong> {{ $lead->service }}</p>
                                        @endif
                                        @if($lead->business_name)
                                        <p style="margin:0 0 8px; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#374151;"><strong style="color:#0b0b0b;">Company:</strong> {{ $lead->business_name }}</p>
                                        @endif
                                        <p style="margin:0 0 8px; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#374151;"><strong style="color:#0b0b0b;">Phone:</strong> {{ $lead->phone }}</p>
                                        <p style="margin:0; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#374151;"><strong style="color:#0b0b0b;">Email:</strong> {{ $lead->email }}</p>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="border-radius:8px; background-color:#00bf63;">
                                        <a href="{{ url('/') }}" style="display:inline-block; padding:13px 28px; font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:700; color:#0b0b0b; text-decoration:none; border-radius:8px;">
                                            Visit MapZoon &rarr;
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:28px 0 0; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#9ca3af;">
                                Warm regards,<br>
                                <strong style="color:#0b0b0b;">The {{ $settings->company_name ?? 'MapZoon' }} Team</strong>
                            </p>
                        </td>
                    </tr>

                    @include('emails.partials.footer')

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
