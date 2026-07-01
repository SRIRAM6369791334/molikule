<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New NEXUS Certification Enquiry</title>
    <style>
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        table { border-collapse: collapse !important; }
        body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background-color: #f4f7f6; color: #334155; }
        
        .email-container { max-width: 600px; margin: 0 auto; width: 100%; }
        .email-card { background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05); margin-top: 30px; margin-bottom: 30px; }
        
        /* Hero Banner Style */
        .header { background-color: #1368B4; padding: 40px 30px; text-align: center; }
        .logo-wrapper { background: #ffffff; padding: 15px 25px; border-radius: 8px; display: inline-block; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header-text { color: #ffffff; font-size: 24px; font-weight: 700; margin-top: 20px; margin-bottom: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        
        .content { padding: 30px 40px 40px; }
        
        .section-title { font-size: 13px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; margin-bottom: 15px; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px; }
        
        /* Two Column Details with Icons */
        .details-table { width: 100%; font-size: 15px; border-collapse: collapse; margin-bottom: 25px; }
        .details-table th { width: 45%; text-align: left; padding: 12px 0; color: #475569; font-weight: 500; border-bottom: 1px dashed #e2e8f0; vertical-align: top; }
        .details-table td { padding: 12px 0; color: #0f172a; font-weight: 600; border-bottom: 1px dashed #e2e8f0; vertical-align: top; }
        .icon { display: inline-block; width: 24px; text-align: center; margin-right: 8px; font-size: 16px; color: #1368B4; }
        
        .segment-badge { background-color: #eaf4fa; color: #1368B4; padding: 4px 10px; border-radius: 4px; font-size: 13px; font-weight: 700; border: 1px solid #bee0f2; }
        
        .message-box { background-color: #f8fafc; border-left: 4px solid #bbd700; padding: 20px; border-radius: 0 6px 6px 0; margin-top: 10px; font-style: italic; font-size: 15px; color: #334155; line-height: 1.6; }
        
        .footer { padding: 30px; text-align: center; background-color: #f1f5f9; font-size: 13px; color: #64748b; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body style="background-color: #f4f7f6; margin: 0 !important; padding: 0 !important;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f4f7f6; padding: 20px;">
        <tr>
            <td align="center">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" class="email-container">
                    <tr>
                        <td align="center">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="email-card">
                                <!-- Hero Banner -->
                                <tr>
                                    <td align="center" class="header">
                                        <div class="logo-wrapper">
                                            <a href="https://molikule.com" target="_blank">
                                                <img src="{{ asset('assets/images/logo1.png') }}" alt="Molikule Green Care" width="150" style="display: block; max-width: 150px;">
                                            </a>
                                        </div>
                                        <h1 class="header-text">NEXUS Certification Enquiry</h1>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td class="content">
                                        <div class="section-title">Client Details</div>
                                        
                                        <table class="details-table" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <th><span class="icon">👤</span> Name:</th>
                                                <td>{{ $enquiry->name }}</td>
                                            </tr>
                                            <tr>
                                                <th><span class="icon">📞</span> Contact No.:</th>
                                                <td>{{ $enquiry->contact_no }}</td>
                                            </tr>
                                            <tr>
                                                <th><span class="icon">📧</span> Email:</th>
                                                <td><a href="mailto:{{ $enquiry->email }}" style="color:#1368B4; text-decoration:none;">{{ $enquiry->email }}</a></td>
                                            </tr>
                                            <tr>
                                                <th><span class="icon">🏢</span> Company:</th>
                                                <td><strong style="color:#0f172a;">{{ $enquiry->company_name }}</strong></td>
                                            </tr>
                                            <tr>
                                                <th><span class="icon">🎯</span> Segment:</th>
                                                <td><span class="segment-badge">{{ $enquiry->segment }}</span></td>
                                            </tr>
                                        </table>
                                        
                                        <div class="section-title" style="margin-top: 30px;">Additional Details / Thoughts</div>
                                        <div class="message-box">
                                            {!! nl2br(e($enquiry->thoughts)) !!}
                                        </div>

                                        <p style="margin-top: 30px; font-size: 13px; color: #94a3b8; text-align: right;">
                                            Received on: {{ now()->format('M d, Y h:i A') }}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="footer">
                                        <p style="margin: 0;">&copy; {{ date('Y') }} Molikule Green Care. All rights reserved.</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
