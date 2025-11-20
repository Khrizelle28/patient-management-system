<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Appointment Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            border: 2px solid #333;
        }
        .header {
            padding: 30px 40px;
            background-color: white;
            border-bottom: 1px solid #e0e0e0;
        }
        .logo-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .logo {
            display: flex;
            align-items: center;
        }
        .logo-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0ea5e9 0%, #10b981 100%);
            margin-right: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 20px;
        }
        .logo-text {
            font-size: 24px;
            font-weight: bold;
            color: #0ea5e9;
        }
        .clinic-name {
            font-size: 14px;
            color: #666;
        }
        .invoice-info {
            text-align: right;
            font-size: 12px;
            color: #666;
        }
        .invoice-title {
            text-align: center;
            font-size: 32px;
            font-weight: bold;
            color: #0ea5e9;
            margin: 20px 0;
            padding: 0 40px;
        }
        .content {
            padding: 0 40px;
        }
        .invoice-to {
            margin: 30px 0;
        }
        .invoice-to h3 {
            color: #0ea5e9;
            font-size: 16px;
            margin-bottom: 5px;
        }
        .patient-name {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        .patient-contact {
            font-size: 14px;
            color: #666;
        }
        .total-section {
            text-align: right;
            margin: 20px 0;
        }
        .total-label {
            font-size: 14px;
            color: #666;
        }
        .total-amount {
            font-size: 28px;
            font-weight: bold;
            color: #0ea5e9;
        }
        .services-table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
        }
        .services-table thead {
            background-color: #0ea5e9;
            color: white;
        }
        .services-table th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }
        .services-table td {
            padding: 12px;
            border-bottom: 1px solid #e0e0e0;
        }
        .services-table tbody tr:last-child td {
            border-bottom: none;
        }
        .text-right {
            text-align: right;
        }
        .payment-info {
            background-color: #f0f9ff;
            padding: 20px;
            border-radius: 8px;
            margin: 30px 0;
        }
        .payment-info h3 {
            color: #0ea5e9;
            font-size: 16px;
            margin-top: 0;
            margin-bottom: 15px;
        }
        .payment-detail {
            display: flex;
            justify-content: space-between;
            margin: 8px 0;
            font-size: 14px;
        }
        .payment-label {
            color: #666;
        }
        .payment-value {
            font-weight: bold;
            color: #333;
        }
        .total-row {
            background-color: #0ea5e9;
            color: white;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 18px;
            font-weight: bold;
        }
        .thank-you {
            text-align: center;
            color: #0ea5e9;
            font-size: 18px;
            padding: 30px 40px 40px;
        }
        .footer {
            padding: 20px 40px;
            background-color: #f9fafb;
            border-top: 1px solid #e0e0e0;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo-section">
                <div>
                    <div class="logo">
                        <div class="logo-icon">T</div>
                        <div>
                            <div class="logo-text">Tejero Medical</div>
                            <div class="clinic-name">and Maternity Clinic</div>
                        </div>
                    </div>
                </div>
                <div class="invoice-info">
                    <div>Invoice No: {{ $data['invoice_number'] }}</div>
                    <div>Date: {{ $data['date'] }}</div>
                </div>
            </div>
        </div>

        <div class="invoice-title">Invoice</div>

        <div class="content">
            <div class="invoice-to">
                <h3>Invoice to:</h3>
                <div class="patient-name">{{ $data['patient_name'] }}</div>
                <div class="patient-contact">{{ $data['patient_contact'] }}</div>
            </div>

            <div class="total-section">
                <div class="total-label">Total due:</div>
                <div class="total-amount">{{ $data['total_amount'] }}</div>
            </div>

            <table class="services-table">
                <thead>
                    <tr>
                        <th>Service</th>
                        <th class="text-right">Price</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['services'] as $service)
                    <tr>
                        <td>{{ $service['name'] }}</td>
                        <td class="text-right">{{ $service['price'] }}</td>
                        <td class="text-right">{{ $service['total'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="payment-info">
                <h3>Payment Method:</h3>
                <div class="payment-detail">
                    <span class="payment-label">{{ $data['payment_method'] }}</span>
                </div>
                <div class="payment-detail">
                    <span class="payment-label">Reference No:</span>
                    <span class="payment-value">{{ $data['reference_number'] }}</span>
                </div>
            </div>
        </div>

        <div class="total-row">
            <span>Total:</span>
            <span>{{ $data['total_amount'] }}</span>
        </div>

        <div class="thank-you">
            Thank you for your appointment!
        </div>

        <div class="footer">
            This is an automated email. Please do not reply to this message.
        </div>
    </div>
</body>
</html>
