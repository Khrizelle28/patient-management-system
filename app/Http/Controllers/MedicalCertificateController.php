<?php

namespace App\Http\Controllers;

use App\Models\MedicalCertificate;
use App\Traits\QrCodeTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class MedicalCertificateController extends Controller
{
    use QrCodeTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $medical_certificates = MedicalCertificate::where('doctor_id', auth()->user()->id)->get();

        return view('medical-certificate.index', compact('medical_certificates'));
    }

    public function generateMedicalCertificate($id)
    {
        $certificate = MedicalCertificate::with(['patient', 'doctor'])->findOrFail($id);
        $qrData = route('medical-certificate.preview', $certificate->id);

        $qrResult = self::generate($qrData);
        $qrBase64 = $qrResult->getDataUri();

        $pdfData = $this->preparePdfData($certificate, $qrBase64);

        $pdf = Pdf::loadView('pdf.medical-certificate', $pdfData);

        $pdf->setPaper('A4', 'portrait');

        $filename = 'medical-certificate.pdf';

        return $pdf->stream($filename);
    }

    /**
     * Prepare data for PDF generation.
     */
    private function preparePdfData(MedicalCertificate $certificate, string $qrBase64): array
    {
        $patient = $certificate->patient;
        $doctor = $certificate->doctor;

        // Calculate age from birthday if age is not set
        $age = $patient->age ?? 'N/A';
        if (($age === 'N/A' || empty($age)) && ! empty($patient->birthday)) {
            $age = \Carbon\Carbon::parse($patient->birthday)->age;
        }

        // Patient sex is not in patient_users table, will show N/A
        $sex = 'F';

        return [
            'qrBase64' => $qrBase64,
            'date' => \Carbon\Carbon::parse($certificate->created_at)->format('F j, Y'),
            'patient_name' => $patient->full_name ?? 'N/A',
            'age' => $age,
            'sex' => $sex,
            'civil_status' => $patient->civil_status ?? 'N/A',
            'address' => $patient->full_address ?? 'N/A',
            'medical_condition' => $certificate->medical_condition ?? 'N/A',
            'remarks' => $certificate->remarks ?? 'N/A',
            'doctor_name' => $doctor->full_name ?? 'N/A',
            'license_number' => $doctor->license_no ?? 'N/A',
            'ptr_number' => $doctor->ptr_no ?? 'N/A',
        ];
    }

    /**
     * Preview the medical certificate PDF.
     */
    public function preview(MedicalCertificate $medicalCertificate): \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\Response
    {
        // Check if PDF was uploaded - prioritize uploaded file
        if ($medicalCertificate->upload_pdf == '1' && ! empty($medicalCertificate->generate_pdf)) {
            $filePath = storage_path('app/public/medical-certificates/'.$medicalCertificate->generate_pdf);

            if (file_exists($filePath)) {
                return response()->file($filePath, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="medical-certificate-'.$medicalCertificate->id.'.pdf"',
                ]);
            }
        }

        // Otherwise generate PDF on the fly
        $medicalCertificate->load(['patient', 'doctor']);
        $qrData = route('medical-certificate.preview', $medicalCertificate->id);
        $qrResult = self::generate($qrData);
        $qrBase64 = $qrResult->getDataUri();

        $pdfData = $this->preparePdfData($medicalCertificate, $qrBase64);

        $pdf = Pdf::loadView('pdf.medical-certificate', $pdfData);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('medical-certificate-'.$medicalCertificate->id.'.pdf');
    }

    /**
     * Download the medical certificate PDF.
     */
    public function download(MedicalCertificate $medicalCertificate): \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\Response
    {
        // Check if PDF was uploaded - prioritize uploaded file
        if ($medicalCertificate->upload_pdf == '1' && ! empty($medicalCertificate->generate_pdf)) {
            $filePath = storage_path('app/public/medical-certificates/'.$medicalCertificate->generate_pdf);

            if (file_exists($filePath)) {
                return response()->download($filePath, 'medical-certificate-'.$medicalCertificate->id.'.pdf');
            }
        }

        // Otherwise generate PDF and download
        $medicalCertificate->load(['patient', 'doctor']);
        $qrData = route('medical-certificate.preview', $medicalCertificate->id); // temporary
        $qrResult = self::generate($qrData);
        $qrBase64 = $qrResult->getDataUri();

        $pdfData = $this->preparePdfData($medicalCertificate, $qrBase64);

        $pdf = Pdf::loadView('pdf.medical-certificate', $pdfData);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('medical-certificate-'.$medicalCertificate->id.'.pdf');
    }

    /**
     * Show the upload form for a medical certificate.
     */
    public function showUploadForm(MedicalCertificate $medicalCertificate): \Illuminate\Contracts\View\View
    {
        return view('medical-certificate.upload', compact('medicalCertificate'));
    }

    /**
     * Upload a PDF for a medical certificate.
     */
    public function upload(Request $request, MedicalCertificate $medicalCertificate): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'pdf_file' => 'required|file|mimes:pdf|max:10240', // 10MB max
        ]);

        try {
            // Delete old file if exists (only if it was uploaded, not generated)
            if ($medicalCertificate->upload_pdf == '1' && ! empty($medicalCertificate->generate_pdf)) {
                $oldFilePath = storage_path('app/public/medical-certificates/'.$medicalCertificate->generate_pdf);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            // Ensure directory exists
            $directory = storage_path('app/public/medical-certificates');
            if (! file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            // Store the uploaded file
            $file = $request->file('pdf_file');
            $filename = 'medical-cert-'.$medicalCertificate->id.'-'.time().'.pdf';

            // Define the destination path
            $destinationPath = storage_path('app/public/medical-certificates/'.$filename);

            // Move the file directly
            if (! $file->move(storage_path('app/public/medical-certificates'), $filename)) {
                throw new \Exception('Failed to move uploaded file to destination.');
            }

            // Verify file was stored
            if (! file_exists($destinationPath)) {
                throw new \Exception('File was not saved to storage. Path: '.$destinationPath);
            }

            // Update the medical certificate record
            $medicalCertificate->update([
                'upload_pdf' => '1',
                'generate_pdf' => $filename,
            ]);

            return redirect()->route('medical-certificate.index')
                ->with('success', 'Medical certificate PDF uploaded successfully. File: '.$filename);
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['pdf_file' => 'Upload failed: '.$e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(MedicalCertificate $medicalCertificate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MedicalCertificate $medicalCertificate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MedicalCertificate $medicalCertificate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MedicalCertificate $medicalCertificate)
    {
        //
    }
}
