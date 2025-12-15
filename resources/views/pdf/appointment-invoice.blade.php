<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice #12345</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            background-color: #f5f5f5;
            padding: 40px 20px;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        .header {
            width: 100%;
            margin-bottom: 50px;
            overflow: hidden;
        }

        .logo-section {
            float: left;
            width: 50%;
        }

        .logo {
            width: 60px;
            height: 60px;
            background: #00a86b;
            border-radius: 50%;
            float: left;
            margin-right: 15px;
            position: relative;
        }

        .logo-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 24px;
            font-weight: bold;
        }

        .clinic-info {
            margin-left: 75px;
            padding-top: 10px;
        }

        .clinic-info h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 5px;
        }

        .clinic-info p {
            color: #666;
            font-size: 14px;
        }

        .invoice-meta {
            float: right;
            width: 45%;
            text-align: right;
        }

        .invoice-meta p {
            margin-bottom: 5px;
            color: #666;
        }

        .content-wrapper {
            width: 100%;
            margin-bottom: 40px;
            overflow: hidden;
        }

        .invoice-to {
            float: left;
            width: 45%;
        }

        .invoice-to h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 16px;
        }

        .customer-name {
            color: #00a8cc;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .customer-phone {
            color: #666;
            font-size: 14px;
        }

        .invoice-title-section {
            float: right;
            width: 50%;
            text-align: right;
        }

        .invoice-title {
            color: #00a8cc;
            font-size: 48px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .total-due-label {
            color: #333;
            font-size: 18px;
            margin-bottom: 5px;
        }

        .total-amount {
            color: #00a8cc;
            font-size: 32px;
            font-weight: bold;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .items-table thead {
            background: #00a8cc;
            color: white;
        }

        .items-table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }

        .items-table th:last-child,
        .items-table td:last-child {
            text-align: right;
        }

        .items-table tbody tr {
            border-bottom: 1px solid #ddd;
        }

        .items-table td {
            padding: 20px 15px;
            color: #333;
        }

        .footer-section {
            width: 100%;
            margin-top: 30px;
            overflow: hidden;
        }

        .payment-method {
            float: left;
            width: 45%;
        }

        .payment-method h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 16px;
        }

        .payment-method p {
            color: #666;
            margin-bottom: 5px;
        }

        .payment-method .reference {
            font-style: italic;
            color: #888;
        }

        .total-box {
            background: #00a8cc;
            color: white;
            padding: 20px 30px;
            float: right;
            width: 250px;
            text-align: right;
        }

        .total-box-label {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .total-box-amount {
            font-size: 32px;
            font-weight: bold;
        }

        .thank-you {
            color: #00a8cc;
            font-size: 18px;
            margin-top: 60px;
            clear: both;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .invoice-container {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="header clearfix">
            <div class="logo-section">
                <div class="logo">
                    <div class="logo-text">T</div>
                </div>
                <div class="clinic-info">
                    <h1>Tejero Medical</h1>
                    <p>and Maternity Clinic</p>
                </div>
            </div>
            <div class="invoice-meta">
                <p><strong>Invoice No:</strong> {{ $data['invoice_number'] }}</p>
                <p><strong>Date:</strong> {{ $data['date'] }}</p>
            </div>
        </div>

        <!-- Content -->
        <div class="content-wrapper clearfix">
            <div class="invoice-to">
                <h3>Invoice to:</h3>
                <div class="customer-name">{{ $data['patient_name'] }}</div>
                {{-- <div class="customer-phone">{{ $data['patient_contact'] }}</div> --}}
            </div>
            <div class="invoice-title-section">
                <div class="invoice-title">Invoice</div>
                <div class="total-due-label">Total due:</div>
                <div class="total-amount">{{ $data['total_amount'] }}</div>
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Service</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['services'] as $service)
                <tr>
                    <td>{{ $service['name'] }}</td>
                    <td>{{ $service['price'] }}</td>
                    <td>{{ $service['total'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Footer -->
        <div class="footer-section clearfix">
            <div class="payment-method">
                <h3>Payment Method:</h3>
                <p>{{ $data['payment_method'] }}</p>
                <p class="reference">Reference No: {{ $data['reference_number'] }}</p>
            </div>
            <div class="total-box">
                <div class="total-box-label">Total:</div>
                <div class="total-box-amount">{{ $data['total_amount'] }}</div>
            </div>
        </div>

        <!-- Thank You -->
        <div class="thank-you">
            Thank you for your appointment!
        </div>
    </div>
</body>
</html>
