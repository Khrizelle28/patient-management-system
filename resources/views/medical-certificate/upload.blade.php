@extends('admin.index')

@section('content')
    <div class="card mt-4">
        <div class="card-header">
            <i class="fa-solid fa-upload"></i>
            Upload Medical Certificate PDF
        </div>
        <div class="card-body">
            <form action="{{ route('medical-certificate.upload', $medicalCertificate->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="patient" class="form-label">Patient</label>
                    <input type="text" class="form-control" id="patient" value="{{ $medicalCertificate->patient->full_name }}" disabled>
                </div>

                <div class="mb-3">
                    <label for="purpose" class="form-label">Purpose</label>
                    <input type="text" class="form-control" id="purpose" value="{{ $medicalCertificate->purpose }}" disabled>
                </div>

                @if($medicalCertificate->upload_pdf && $medicalCertificate->generate_pdf)
                    <div class="alert alert-info">
                        <i class="fa-solid fa-info-circle"></i>
                        A PDF file is already uploaded for this medical certificate. Uploading a new file will replace the existing one.
                    </div>
                @endif

                <div class="mb-3">
                    <label for="pdf_file" class="form-label">PDF File <span class="text-danger">*</span></label>
                    <input type="file" class="form-control @error('pdf_file') is-invalid @enderror" id="pdf_file" name="pdf_file" accept=".pdf" required>
                    @error('pdf_file')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                    <small class="form-text text-muted">Maximum file size: 10MB</small>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-upload"></i> Upload PDF
                    </button>
                    <a href="{{ route('medical-certificate.index') }}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Back
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
