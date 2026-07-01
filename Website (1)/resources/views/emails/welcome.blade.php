<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Molikule</title>
    <style>
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        table { border-collapse: collapse !important; }
        body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background-color: #f4f7f6; color: #334155; }
        
        .email-container { max-width: 600px; margin: 0 auto; width: 100%; }
        .email-card { background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05); margin-top: 30px; margin-bottom: 30px; }
        
        /* Hero Banner */
        .header { background-color: #1368B4; padding: 40px 30px; text-align: center; }
        .logo-wrapper { background: #ffffff; padding: 15px 25px; border-radius: 8px; display: inline-block; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header-text { color: #ffffff; font-size: 24px; font-weight: 700; margin-top: 20px; margin-bottom: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        
        .content { padding: 40px; text-align: center; }
        
        h1 { color: #0f172a; font-size: 24px; font-weight: 700; margin: 0 0 20px; letter-spacing: -0.5px; }
        p { font-size: 16px; line-height: 1.6; margin: 0 0 20px; color: #475569; }
        
        .btn-wrapper { text-align: center; margin: 35px 0 20px; }
        .btn { display: inline-block; background-color: #bbd700; color: #1e3a34 !important; font-weight: 700; text-decoration: none; padding: 15px 35px; border-radius: 8px; font-size: 16px; box-shadow: 0 4px 6px rgba(187, 215, 0, 0.2); }
        
        /* Footer */
        .footer { padding: 30px; text-align: center; background-color: #f1f5f9; font-size: 13px; color: #64748b; border-top: 1px solid #e2e8f0; }
        .support-info { font-weight: 600; color: #0f172a; margin-bottom: 15px; font-size: 14px; }
        .social-icons { margin: 15px 0; }
        .social-icons a { display: inline-block; margin: 0 10px; color: #1368B4; text-decoration: none; font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; }
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
                                                <img src="{{ asset('assets/images/logo1.png') }}" alt="Molikule Green Care" width="150" style="display: block; max-width: 150px;">
                                            </a>
                                        </div>
                                        <h1 class="header-text">Welcome to the Family!</h1>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content">
                                        <p style="font-size: 18px; color: #0f172a; font-weight: 600;">Dear {{ $user->name ?? 'Customer' }},</p>
                                        
                                        <p>Thank you for registering with Molikule Green Care. We are thrilled to welcome you to our community of eco-conscious individuals and organizations.</p>
                                        
                                        <p>Our mission is to provide high-performance, sustainable hygiene and cleaning solutions that protect both people and the planet. You now have full access to explore our premium range of products.</p>
                                        
                                        <div class="btn-wrapper">
                                            <a href="{{ url('/') }}" class="btn">Explore Our Products</a>
                                        </div>
                                        
                                        <p style="margin-top: 30px; font-size: 15px;">Best regards,<br><strong style="color:#0f172a;">The Molikule Green Care Team</strong></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="footer">
                                        <div class="support-info">
                                            Need help? Call Support: <a href="tel:+918220000000" style="color: #1368B4;">+91 822 000 0000</a><br>
                                        </div>
                                        
                                        <div class="social-icons">
                                            <a href="https://facebook.com/molikule">Facebook</a> &bull; 
                                            <a href="https://instagram.com/molikule">Instagram</a> &bull; 
                                            <a href="https://linkedin.com/company/molikule">LinkedIn</a>
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
