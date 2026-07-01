<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your OTP - Molikule</title>
    <style>
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        table { border-collapse: collapse !important; }
        body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background-color: #f4f7f6; color: #334155; }
        
        .email-container { max-width: 600px; margin: 0 auto; width: 100%; }
        .email-card { background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05); margin-top: 30px; margin-bottom: 30px; }
        
        .header { background-color: #1368B4; padding: 40px 30px; text-align: center; }
        .logo-wrapper { background: #ffffff; padding: 15px 25px; border-radius: 8px; display: inline-block; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header-text { color: #ffffff; font-size: 24px; font-weight: 700; margin-top: 20px; margin-bottom: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        
        .content { padding: 40px; text-align: center; }
        
        p { font-size: 16px; line-height: 1.6; margin: 0 0 20px; color: #475569; }
        
        .otp-box { background-color: #f8fafc; border: 2px dashed #1368B4; border-radius: 8px; margin: 30px auto; padding: 20px 40px; display: inline-block; }
        .otp-code { color: #1368B4; font-size: 36px; font-weight: 800; letter-spacing: 8px; margin: 0; line-height: 1; }
        
        .footer { padding: 30px; text-align: center; background-color: #f1f5f9; font-size: 13px; color: #64748b; border-top: 1px solid #e2e8f0; }
        .support-info { font-weight: 600; color: #0f172a; margin-bottom: 15px; font-size: 14px; }
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
                                <tr>
                                    <td align="center" class="header">
                                        <div class="logo-wrapper">
                                            <a href="https://molikule.com" target="_blank">
                                                <img src="{{ asset('assets/images/logo1.png') }}" alt="Molikule" width="150" style="display: block; max-width: 150px;">
                                            </a>
                                        </div>
                                        <h1 class="header-text">Security Code</h1>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content">
                                        <p>Hello,</p>
                                        <p>Use the following 6-digit One-Time Password (OTP) to complete your request. This code is valid for 15 minutes.</p>
                                        
                                        <div class="otp-box">
                                            <div class="otp-code">{{ $otp }}</div>
                                        </div>
                                        
                                        <p style="font-size: 14px;">If you did not request this code, please safely ignore this email.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="footer">
                                        <div class="support-info">
                                            Need help? Call Support: <a href="tel:+918220000000" style="color: #1368B4;">+91 822 000 0000</a>
                                        </div>
                                        <p style="margin: 0; margin-top: 20px;">&copy; {{ date('Y') }} Molikule Green Care. All rights reserved.</p>
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
