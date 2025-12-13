<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Reminder</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 20px;
            line-height: 1.6;
        }

        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }

        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            font-size: 36px;
            backdrop-filter: blur(10px);
        }

        .email-header h1 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .email-header p {
            font-size: 16px;
            opacity: 0.95;
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
            color: #667eea;
        }

        .message-text {
            color: #555;
            font-size: 16px;
            margin-bottom: 30px;
            line-height: 1.8;
        }

        .appointment-card {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
            border-left: 5px solid #667eea;
        }

        .appointment-card h2 {
            color: #667eea;
            font-size: 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .appointment-detail {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
            padding: 12px;
            background: white;
            border-radius: 8px;
        }

        .appointment-detail:last-child {
            margin-bottom: 0;
        }

        .detail-icon {
            width: 40px;
            height: 40px;
            background: #667eea;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .detail-content {
            flex: 1;
        }

        .detail-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
            font-weight: 600;
        }

        .detail-value {
            font-size: 16px;
            color: #333;
            font-weight: 500;
        }

        .info-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .info-box p {
            color: #856404;
            font-size: 14px;
            margin: 0;
        }

        .info-box strong {
            display: block;
            margin-bottom: 8px;
            font-size: 16px;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
        }

        .btn {
            flex: 1;
            padding: 15px 20px;
            text-align: center;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-secondary {
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
        }

        .contact-section {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
        }

        .contact-section h3 {
            color: #333;
            font-size: 18px;
            margin-bottom: 15px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
            color: #555;
        }

        .contact-item:last-child {
            margin-bottom: 0;
        }

        .contact-icon {
            width: 32px;
            height: 32px;
            background: #667eea;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
        }

        .footer {
            background: #f8f9fa;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e0e0e0;
        }

        .footer-logo {
            margin-bottom: 15px;
        }

        .footer-logo-circle {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            margin: 0 auto 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 22px;
        }

        .footer-text {
            color: #666;
            font-size: 13px;
            margin-bottom: 5px;
        }

        .footer-clinic {
            color: #667eea;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .footer-note {
            color: #999;
            font-size: 12px;
            font-style: italic;
            margin-top: 20px;
        }

        @media only screen and (max-width: 600px) {
            .email-wrapper {
                border-radius: 0;
            }

            .email-header {
                padding: 30px 20px;
            }

            .email-body {
                padding: 30px 20px;
            }

            .appointment-card {
                padding: 20px;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <!-- Header -->
        <div class="email-header">
            <div class="header-icon">📅</div>
            <h1>Appointment Reminder</h1>
            <p>Don't forget your upcoming appointment</p>
        </div>

        <!-- Body -->
        <div class="email-body">
            <div class="greeting">
                Hello, <strong>{{ $data['patient_name'] }}</strong>
            </div>

            <p class="message-text">
                This is a friendly reminder about your upcoming appointment at Tejero Medical and Maternity Clinic.
                We look forward to seeing you!
            </p>

            <!-- Appointment Details Card -->
            <div class="appointment-card">
                <h2>📋 Appointment Details</h2>

                <div class="appointment-detail">
                    <div class="detail-icon">👨‍⚕️</div>
                    <div class="detail-content">
                        <div class="detail-label">Doctor</div>
                        <div class="detail-value">{{ $data['doctor_name'] }}</div>
                    </div>
                </div>

                <div class="appointment-detail">
                    <div class="detail-icon">📅</div>
                    <div class="detail-content">
                        <div class="detail-label">Date</div>
                        <div class="detail-value">{{ $data['appointment_date'] }}</div>
                    </div>
                </div>

                <div class="appointment-detail">
                    <div class="detail-icon">🕐</div>
                    <div class="detail-content">
                        <div class="detail-label">Time</div>
                        <div class="detail-value">{{ $data['appointment_time'] }}</div>
                    </div>
                </div>

                <div class="appointment-detail">
                    <div class="detail-icon">🏥</div>
                    <div class="detail-content">
                        <div class="detail-label">Service Type</div>
                        <div class="detail-value">{{ $data['service_type'] }}</div>
                    </div>
                </div>
            </div>

            <!-- Important Info Box -->
            <div class="info-box">
                <strong>⚠️ Important Reminders</strong>
                <p>
                    Please arrive 10-15 minutes early to complete any necessary paperwork.
                    If you need to reschedule or cancel, please contact us at least 24 hours in advance.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="{{ $data['confirm_url'] ?? '#' }}" class="btn btn-primary">Confirm Appointment</a>
                <a href="{{ $data['reschedule_url'] ?? '#' }}" class="btn btn-secondary">Reschedule</a>
            </div>

            <!-- Contact Section -->
            <div class="contact-section">
                <h3>Need Help?</h3>
                <div class="contact-item">
                    <div class="contact-icon">📞</div>
                    <span>{{ $data['clinic_phone'] ?? '(123) 456-7890' }}</span>
                </div>
                <div class="contact-item">
                    <div class="contact-icon">✉️</div>
                    <span>{{ $data['clinic_email'] ?? 'info@tejeromedical.com' }}</span>
                </div>
                <div class="contact-item">
                    <div class="contact-icon">📍</div>
                    <span>{{ $data['clinic_address'] ?? 'Tejero Medical and Maternity Clinic' }}</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-logo">
                <div class="footer-logo-circle">T</div>
                <div class="footer-clinic">Tejero Medical and Maternity Clinic</div>
            </div>
            <p class="footer-text">Providing Quality Healthcare with Compassion</p>
            <p class="footer-note">
                This is an automated reminder. Please do not reply to this email.
            </p>
        </div>
    </div>
</body>
</html>