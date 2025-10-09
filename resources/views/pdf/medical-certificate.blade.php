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
            font-size: 16px;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 20px;
        }

        .certificate-container {
            padding: 20px;
            min-height: 80vh;
            position: relative;
        }

        .header {
            text-align: center;
            /* margin-bottom: 20px; */
        }

        .clinic-logo {
            /* width: 100$; */
            height: 200px;
            display: inline-block;
            vertical-align: middle;
            /* margin-right: 20px; */
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
            /* opacity: 0.1; */
            z-index: -1;
            width: 600px;
            height: 450px;
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
            font-size: 30px;
            font-weight: bold;
            color: #d32f2f;
            margin: 0;
        }

        .clinic-subtitle {
            font-size: 20px;
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
            /* margin: 20px 0; */
            margin-bottom: 20px;
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
            min-width: 10px;
            height: 23px;
            padding: 2px 5px;
        }

        .remarks-section {
            margin: 30px 0;
        }

        .remarks-lines {
            border-bottom: 1px solid #000;
            height: 25px;
            margin: 5px 0;
        }

        .signature-section {
            margin-top: 100px;
            text-align: right;
            width: 49%;
            display: inline-block;
            vertical-align: top;
        }

        .qr-section {
            margin-top: 110px;
            text-align: left;
            width: 49%;
            display: inline-block;
            vertical-align: top;
        }

        .footer__qr {
            width: 120px;
            height: 120px;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            margin: 20px 0 5px auto;
            height: 40px;
            width: 200px;
        }

        .doctor-info {
            margin: 30px 0;
            font-size: 16px;
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
            <img src="{{ public_path('assets/img/pdf/with-watermark.jpg') }}" alt="Watermark">
        </div>

        <!-- Header -->
        <div class="header">
            <div class="clinic-logo">
                <img src="{{ public_path('assets/img/pdf/brand-logo.jpg') }}" alt="Tejero Medical Clinic Logo">
            </div>
            {{-- <div class="clinic-name">
                <h1 class="clinic-title">TEJERO</h1>
                <div class="clinic-subtitle">MEDICAL AND MATERNITY CLINIC</div>
                <div class="clinic-address">273 Tejero, General Trias, Cavite</div>
            </div> --}}
        </div>

        <!-- Divider -->
        <div class="divider">**********************************************************************************</div>

        <!-- Date -->
        <div class="date-field">
            Date : <span  style="font-weight: bold; text-decoration: underline;">{{ $date }}</span>
        </div>

        <!-- Content -->
        <div class="content">
            <p>To Whom It May Concern:</p>
            <div class="certificate-text">
                This is to certify that <span class="underline" style="font-weight: bold;">{{ $patient_name }}</span>
                age <span class="underline" style="font-weight: bold;">{{ $age }}</span>,
                sex <span class="underline" style="font-weight: bold;">{{ $sex }}</span>,
                civil status <span class="underline" style="font-weight: bold;">{{ $civil_status }}</span>,
                residing at <span class="underline" style="font-weight: bold;">{{ $address }}</span>
                was seen, examined and has been under my care for the following medical condition:
                <div class="underline" style="font-weight: bold;">
                    {{ $medical_condition }}
                </div>
            </div>

            <div class="certificate-text" style="margin-top: 40px;">
                This certificate is issued upon request to be used exclusively for medical purposes.
            </div>

            <!-- Remarks Section -->
            <div class="remarks-section">
                <strong>REMARKS:</strong>
                <div class="remarks-lines" style="font-weight: bold;">{{ $remarks }}</div>
            </div>
        </div>
        <div class="qr-section">
            <img class="footer__qr d-block" src="{{ $qrBase64 }}" alt="QR">
           <br><p style="font-size:80%;">(Scan QR Code to validate)</p>
        </div>

        <!-- Signature Section -->
        <div class="signature-section">
            <div class="doctor-info">
                <span class="underline" style="line-height: 1.85; font-weight: bold;">{{ $doctor_name }}</span>, M.D.<br>
                Lic. No. <span class="underline" style="line-height: 1.85;font-weight: bold;"> {{ $license_number }}</span><br>
                PTR No. <span class="underline" style="line-height: 1.85;font-weight: bold;">{{ $ptr_number }} </span>
            </div>
        </div>
    </div>
</body>
</html>