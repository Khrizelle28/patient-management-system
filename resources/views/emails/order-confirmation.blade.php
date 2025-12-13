<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            padding: 40px 20px;
            line-height: 1.6;
        }

        .email-wrapper {
            max-width: 650px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 50px rgba(0,0,0,0.25);
        }

        .email-header {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            padding: 50px 30px;
            text-align: center;
            color: white;
            position: relative;
        }

        .success-checkmark {
            width: 100px;
            height: 100px;
            background: white;
            border-radius: 50%;
            margin: 0 auto 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 50px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            animation: scaleIn 0.5s ease-out;
        }

        @keyframes scaleIn {
            0% {
                transform: scale(0);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }

        .email-header h1 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .email-header p {
            font-size: 16px;
            opacity: 0.95;
        }

        .email-body {
            padding: 40px 30px;
        }

        .greeting {
            font-size: 22px;
            color: #333;
            margin-bottom: 15px;
        }

        .greeting strong {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .message-text {
            color: #666;
            font-size: 16px;
            margin-bottom: 35px;
            line-height: 1.7;
        }

        .order-summary-card {
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 35px;
            border-left: 6px solid #f5576c;
        }

        .order-summary-card h2 {
            color: #d63447;
            font-size: 22px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .order-info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 25px;
        }

        .order-info-item {
            background: white;
            padding: 18px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .info-label {
            font-size: 11px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 6px;
            font-weight: 600;
        }

        .info-value {
            font-size: 16px;
            color: #333;
            font-weight: 600;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 20px;
            background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
            color: #0a5f38;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .items-section {
            margin-bottom: 35px;
        }

        .items-section h3 {
            color: #333;
            font-size: 20px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 12px;
            margin-bottom: 12px;
            transition: all 0.3s ease;
        }

        .order-item:hover {
            background: #f0f0f0;
            transform: translateX(5px);
        }

        .item-details {
            flex: 1;
        }

        .item-name {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .item-meta {
            font-size: 14px;
            color: #999;
        }

        .item-price {
            text-align: right;
        }

        .item-quantity {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }

        .item-total {
            font-size: 18px;
            font-weight: 700;
            color: #f5576c;
        }

        .pricing-summary {
            background: linear-gradient(135deg, #e0c3fc 0%, #8ec5fc 100%);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 35px;
        }

        .pricing-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            font-size: 15px;
            color: #555;
        }

        .pricing-row.total {
            border-top: 2px solid white;
            margin-top: 10px;
            padding-top: 20px;
            font-size: 22px;
            font-weight: 700;
            color: #333;
        }

        .pickup-info-box {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            border-left: 5px solid #00b4db;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 35px;
        }

        .pickup-info-box h3 {
            color: #00796b;
            font-size: 18px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .pickup-details {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-top: 15px;
        }

        .pickup-detail-row {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            gap: 12px;
        }

        .pickup-detail-row:last-child {
            margin-bottom: 0;
        }

        .pickup-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
        }

        .pickup-text {
            font-size: 15px;
            color: #333;
        }

        .pickup-text strong {
            display: block;
            font-size: 13px;
            color: #999;
            font-weight: 500;
            margin-bottom: 3px;
        }

        .action-button {
            display: block;
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 16px;
            margin-bottom: 20px;
            box-shadow: 0 5px 20px rgba(245, 87, 108, 0.3);
            transition: all 0.3s ease;
        }

        .help-section {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 25px;
            text-align: center;
            margin-bottom: 35px;
        }

        .help-section h3 {
            color: #333;
            font-size: 18px;
            margin-bottom: 15px;
        }

        .help-section p {
            color: #666;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .contact-methods {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .contact-method {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: white;
            border-radius: 8px;
            color: #555;
            font-size: 14px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .contact-icon {
            width: 28px;
            height: 28px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 13px;
        }

        .footer {
            background: linear-gradient(135deg, #434343 0%, #000000 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }

        .footer-logo {
            margin-bottom: 20px;
        }

        .footer-logo-circle {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border-radius: 50%;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 26px;
            box-shadow: 0 5px 15px rgba(245, 87, 108, 0.4);
        }

        .footer-clinic {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .footer-tagline {
            color: #ccc;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 25px;
        }

        .social-icon {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .social-icon:hover {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            transform: translateY(-3px);
        }

        .footer-note {
            color: #999;
            font-size: 12px;
            font-style: italic;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        @media only screen and (max-width: 600px) {
            body {
                padding: 20px 10px;
            }

            .email-wrapper {
                border-radius: 10px;
            }

            .email-header {
                padding: 35px 20px;
            }

            .email-header h1 {
                font-size: 26px;
            }

            .email-body {
                padding: 30px 20px;
            }

            .order-info-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .order-item {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }

            .item-price {
                text-align: center;
            }

            .contact-methods {
                flex-direction: column;
                align-items: stretch;
            }

            .contact-method {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <!-- Header -->
        <div class="email-header">
            <div class="success-checkmark">✓</div>
            <h1>Order Confirmed!</h1>
            <p>Thank you for your order</p>
        </div>

        <!-- Body -->
        <div class="email-body">
            <div class="greeting">
                Hello, <strong>{{ $data['customer_name'] }}</strong>
            </div>

            <p class="message-text">
                Great news! Your order has been confirmed and is being prepared for pickup.
                We've received your payment and your medicines will be ready soon.
            </p>

            <!-- Order Summary Card -->
            <div class="order-summary-card">
                <h2>📦 Order Summary</h2>

                <div class="order-info-grid">
                    <div class="order-info-item">
                        <div class="info-label">Order Number</div>
                        <div class="info-value">#{{ $data['order_number'] }}</div>
                    </div>
                    <div class="order-info-item">
                        <div class="info-label">Order Date</div>
                        <div class="info-value">{{ $data['order_date'] }}</div>
                    </div>
                    <div class="order-info-item">
                        <div class="info-label">Payment Method</div>
                        <div class="info-value">{{ $data['payment_method'] }}</div>
                    </div>
                    <div class="order-info-item">
                        <div class="info-label">Order Status</div>
                        <div class="info-value">
                            <span class="status-badge">{{ $data['order_status'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items Section -->
            <div class="items-section">
                <h3>🛍️ Order Items</h3>
                @foreach($data['items'] as $item)
                <div class="order-item">
                    <div class="item-details">
                        <div class="item-name">{{ $item['name'] }}</div>
                        <div class="item-meta">{{ $item['description'] ?? 'Medicine' }}</div>
                    </div>
                    <div class="item-price">
                        <div class="item-quantity">Qty: {{ $item['quantity'] }}</div>
                        <div class="item-total">{{ $item['total'] }}</div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pricing Summary -->
            <div class="pricing-summary">
                <div class="pricing-row">
                    <span>Subtotal</span>
                    <span>{{ $data['subtotal'] }}</span>
                </div>
                @if(isset($data['discount']) && $data['discount'] > 0)
                <div class="pricing-row">
                    <span>Discount</span>
                    <span>-{{ $data['discount'] }}</span>
                </div>
                @endif
                @if(isset($data['tax']) && $data['tax'] > 0)
                <div class="pricing-row">
                    <span>Tax</span>
                    <span>{{ $data['tax'] }}</span>
                </div>
                @endif
                <div class="pricing-row total">
                    <span>Total Amount</span>
                    <span>{{ $data['total_amount'] }}</span>
                </div>
            </div>

            <!-- Pickup Information -->
            <div class="pickup-info-box">
                <h3>📍 Pickup Information</h3>
                <p style="color: #555; font-size: 14px; margin-bottom: 15px;">
                    Your order will be ready for pickup at our clinic. Please bring a valid ID.
                </p>
                <div class="pickup-details">
                    <div class="pickup-detail-row">
                        <div class="pickup-icon">👤</div>
                        <div class="pickup-text">
                            <strong>Pickup Person</strong>
                            {{ $data['pickup_name'] }}
                        </div>
                    </div>
                    <div class="pickup-detail-row">
                        <div class="pickup-icon">📞</div>
                        <div class="pickup-text">
                            <strong>Contact Number</strong>
                            {{ $data['pickup_contact'] }}
                        </div>
                    </div>
                    <div class="pickup-detail-row">
                        <div class="pickup-icon">🏥</div>
                        <div class="pickup-text">
                            <strong>Pickup Location</strong>
                            {{ $data['clinic_address'] ?? 'Tejero Medical and Maternity Clinic' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Button -->
            <a href="{{ $data['order_tracking_url'] ?? '#' }}" class="action-button">
                Track Your Order
            </a>

            <!-- Help Section -->
            <div class="help-section">
                <h3>Need Assistance?</h3>
                <p>Our support team is here to help you with any questions or concerns.</p>
                <div class="contact-methods">
                    <div class="contact-method">
                        <div class="contact-icon">📞</div>
                        <span>{{ $data['clinic_phone'] ?? '(032) 123-4567' }}</span>
                    </div>
                    <div class="contact-method">
                        <div class="contact-icon">✉️</div>
                        <span>{{ $data['clinic_email'] ?? 'support@tejeromedical.com' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-logo">
                <div class="footer-logo-circle">T</div>
                <div class="footer-clinic">Tejero Medical and Maternity Clinic</div>
                <div class="footer-tagline">Your Health, Our Priority</div>
            </div>

            <div class="social-links">
                <a href="#" class="social-icon">📘</a>
                <a href="#" class="social-icon">📷</a>
                <a href="#" class="social-icon">🐦</a>
            </div>

            <p class="footer-note">
                This is an automated email. Please do not reply to this message.<br>
                If you have any questions, please contact our support team.
            </p>
        </div>
    </div>
</body>
</html>