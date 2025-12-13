<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Invoice</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            padding: 40px 20px;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        .email-header {
            background: linear-gradient(135deg, #00a8cc 0%, #00a86b 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }

        .header-icon {
            width: 80px;
            height: 80px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
        }

        .email-header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .email-header p {
            font-size: 16px;
            opacity: 0.9;
        }

        .email-body {
            padding: 40px 30px;
        }

        .greeting {
            font-size: 20px;
            color: #333;
            margin-bottom: 20px;
        }

        .greeting strong {
            color: #00a8cc;
        }

        .message-box {
            background: #f0f9ff;
            border-left: 4px solid #00a8cc;
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .message-box p {
            color: #555;
            font-size: 16px;
            line-height: 1.7;
            margin-bottom: 15px;
        }

        .message-box p:last-child {
            margin-bottom: 0;
        }

        .attachment-notice {
            background: linear-gradient(135deg, #fff3cd 0%, #ffe8a1 100%);
            border-left: 5px solid #ffc107;
            padding: 20px 25px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .attachment-notice h3 {
            color: #856404;
            font-size: 18px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .attachment-notice p {
            color: #856404;
            font-size: 15px;
            line-height: 1.6;
        }

        .invoice-summary {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
        }

        .invoice-summary h3 {
            color: #333;
            font-size: 18px;
            margin-bottom: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .summary-row:last-child {
            border-bottom: none;
            margin-top: 10px;
            padding-top: 15px;
            border-top: 2px solid #00a8cc;
        }

        .summary-label {
            color: #666;
            font-size: 15px;
        }

        .summary-value {
            color: #333;
            font-size: 15px;
            font-weight: 600;
        }

        .summary-row:last-child .summary-label,
        .summary-row:last-child .summary-value {
            font-size: 20px;
            font-weight: 700;
            color: #00a8cc;
        }

        .contact-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px 25px;
            text-align: center;
        }

        .contact-section p {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .contact-section a {
            color: #00a8cc;
            text-decoration: none;
            font-weight: 600;
        }

        .footer {
            background: #f8f9fa;
            padding: 25px 30px;
            text-align: center;
            border-top: 1px solid #e0e0e0;
        }

        .footer-logo {
            margin-bottom: 15px;
        }

        .footer-logo-circle {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #00a8cc 0%, #00a86b 100%);
            border-radius: 50%;
            margin: 0 auto 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 22px;
        }

        .footer-clinic {
            color: #333;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .footer-tagline {
            color: #999;
            font-size: 13px;
            margin-bottom: 15px;
        }

        .footer-note {
            color: #999;
            font-size: 12px;
            font-style: italic;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #e0e0e0;
        }

        @media only screen and (max-width: 600px) {
            body {
                padding: 20px 10px;
            }

            .email-container {
                border-radius: 0;
            }

            .email-header {
                padding: 30px 20px;
            }

            .email-body {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <div class="header-icon">💳</div>
            <h1>Payment Received</h1>
            <p>Your appointment invoice</p>
        </div>

        <!-- Body -->
        <div class="email-body">
            <div class="greeting">
                Hello, <strong>{{ $data['patient_name'] }}</strong>
            </div>

            <div class="message-box">
                <p>Thank you for your payment! We have successfully received your payment for your appointment at Tejero Medical and Maternity Clinic.</p>
                <p>Your appointment has been confirmed and scheduled. We look forward to seeing you!</p>
            </div>

            <!-- Attachment Notice -->
            <div class="attachment-notice">
                <h3>📎 Invoice Attached</h3>
                <p><strong>Please see the attached PDF file for your official payment invoice and receipt.</strong> You can download, save, or print this document for your records.</p>
            </div>

            <!-- Quick Summary -->
            {{-- <div class="invoice-summary">
                <h3>Payment Summary</h3>
                <div class="summary-row">
                    <span class="summary-label">Invoice Number:</span>
                    <span class="summary-value">#{{ $data['invoice_number'] }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Payment Date:</span>
                    <span class="summary-value">{{ $data['date'] }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Payment Method:</span>
                    <span class="summary-value">{{ $data['payment_method'] }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Reference Number:</span>
                    <span class="summary-value">{{ $data['reference_number'] }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Total Paid:</span>
                    <span class="summary-value">{{ $data['total_amount'] }}</span>
                </div>
            </div> --}}

            <!-- Contact Section -->
            <div class="contact-section">
                <p>If you have any questions or concerns, please don't hesitate to contact us.</p>
                <p>Email: <a href="mailto:info@tejeromedical.com">info@tejeromedical.com</a> | Phone: (032) 123-4567</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-logo">
                <div class="footer-logo-circle">T</div>
                <div class="footer-clinic">Tejero Medical and Maternity Clinic</div>
                <div class="footer-tagline">Providing Quality Healthcare with Compassion</div>
            </div>
            <p class="footer-note">
                This is an automated email. Please do not reply to this message.
            </p>
        </div>
    </div>
</body>
</html>
