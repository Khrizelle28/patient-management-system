<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Certificate</title>
    <style>
        @page {
            margin: 1cm;
            size: A4;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 20px;
        }

        .certificate-container {
            border: 3px solid #000;
            padding: 20px;
            min-height: 90vh;
            position: relative;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .clinic-logo {
            width: 80px;
            height: 80px;
            display: inline-block;
            vertical-align: middle;
            margin-right: 20px;
            position: relative;
        }

        .clinic-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .watermark-logo {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.1;
            z-index: -1;
            width: 300px;
            height: 300px;
        }

        .watermark-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .clinic-name {
            display: inline-block;
            vertical-align: middle;
            text-align: left;
        }

        .clinic-title {
            font-size: 24px;
            font-weight: bold;
            color: #d32f2f;
            margin: 0;
        }

        .clinic-subtitle {
            font-size: 14px;
            color: #000;
            margin: 2px 0;
        }

        .clinic-address {
            font-size: 12px;
            color: #000;
            margin: 2px 0;
        }

        .divider {
            text-align: center;
            margin: 20px 0;
            letter-spacing: 2px;
            font-size: 14px;
        }

        .content {
            margin: 30px 0;
            line-height: 1.8;
        }

        .date-field {
            text-align: right;
            margin-bottom: 20px;
        }

        .certificate-text {
            margin: 20px 0;
            text-align: justify;
        }

        .patient-info {
            display: inline-block;
        }

        .underline {
            border-bottom: 1px solid #000;
            display: inline-block;
            min-width: 200px;
            padding: 2px 5px;
        }

        .remarks-section {
            margin: 30px 0;
        }

        .remarks-lines {
            border-bottom: 1px solid #000;
            height: 20px;
            margin: 5px 0;
        }

        .signature-section {
            position: absolute;
            bottom: 50px;
            right: 50px;
            text-align: center;
            width: 250px;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            margin: 20px 0 5px 0;
            height: 40px;
        }

        .doctor-info {
            font-size: 11px;
            line-height: 1.5;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 60px;
            color: rgba(0, 0, 0, 0.05);
            z-index: -1;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <!-- Watermark -->
        <div class="watermark-logo">
            <img src="{{ public_path('assets/img/pdf/brand-logo.jpg') }}" alt="Watermark">
        </div>
        
        <!-- Header -->
        <div class="header">
            <div class="clinic-logo">
                <img src="{{ public_path('assets/img/pdf/with-watermark.jpg') }}" alt="Tejero Medical Clinic Logo">
            </div>
            <div class="clinic-name">
                <h1 class="clinic-title">TEJERO</h1>
                <div class="clinic-subtitle">MEDICAL AND MATERNITY CLINIC</div>
                <div class="clinic-address">273 Tejero, General Trias, Cavite</div>
            </div>
        </div>

        <!-- Divider -->
        <div class="divider">**************************************************</div>

        <!-- Date -->
        <div class="date-field">
            Date {{ $date ?? 'N/A' }}
        </div>

        <!-- Content -->
        <div class="content">
            <p>To Whom It May Concern:</p>
            
            <div class="certificate-text">
                This is to certify that <span class="underline">{{ $patient_name ?? 'N/A' }}</span>
            </div>
            
            <div class="certificate-text">
                age <span class="underline">{{ $age ?? 'N/A' }}</span>, 
                sex <span class="underline">{{ $sex  ?? 'N/A' }}</span>, 
                civil status <span class="underline">{{ $civil_status  ?? 'N/A' }}</span>, 
                residing at <span class="underline">{{ $address ?? 'N/A' }}</span>
            </div>
            
            <div class="certificate-text">
                was seen, examined and has been under my care for the following medical condition:
            </div>
            
            <div class="certificate-text">
                <div class="underline" style="min-width: 400px; min-height: 40px; padding: 10px;">
                    {{ $medical_condition ?? 'N/A' }}
                </div>
            </div>

            <div class="certificate-text" style="margin-top: 40px;">
                This certificate is issued upon request to be used exclusively for medical purposes.
            </div>

            <!-- Remarks Section -->
            <div class="remarks-section">
                <strong>REMARKS:</strong>
                <div class="remarks-lines">{{ $remarks ?? 'N/A' }}</div>
                <div class="remarks-lines"></div>
            </div>
        </div>

        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-line"></div>
            <div class="doctor-info">
                <strong>{{ $doctor_name ?? 'N/A' }}, M.D.</strong><br>
                Lic. No. {{ $license_number ?? 'N/A' }}<br>
                PTR No. {{ $ptr_number ?? 'N/A' }}
            </div>
        </div>
    </div>
</body>
</html>