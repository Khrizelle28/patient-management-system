@extends('admin.index')

@section('content')
    <div class="card mt-4">
        <div class="card-header">
            <i class="fa-solid fa-book"></i>
            Medical Certificate Records
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="tableProduct">
                <thead>
                    <tr>
                        <th>Appointment ID</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Purpose</th>
                        <th>PDF</th>
                        <th>Date Generated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Appointment ID</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Purpose</th>
                        <th>PDF</th>
                        <th>Date Generated</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
                <tbody>
                    @forelse($medical_certificates ?? [] as $medical_certificate)
                        <tr>
                            <td>{{ $medical_certificate->id }}</td>
                            <td>{{ $medical_certificate->patient->full_name }}</td>
                            <td>{{ $medical_certificate->doctor->full_name ?? 'N/A' }}</td>
                            <td>{{ $medical_certificate->purpose }}</td>
                            <td>{{ $medical_certificate->upload_pdf == true ? 'Uploaded' : 'Generated' }}</td>
                            <td>{{ Carbon\Carbon::parse($medical_certificate->created_at)->format('F j, Y') }}</td>
                            <td>
                                <div class="kebab-menu">
                                    <div class="kebab-icon">⋮</div>
                                        <div class="menu-options">
                                            <a href="{{ route('medical-certificate.download', $medical_certificate->id) }}">Download</a>
                                            @if($medical_certificate->upload_pdf != '1')
                                                <a href="{{ route('medical-certificate.showUploadForm', $medical_certificate->id) }}">Upload</a>
                                            @endif
                                            <a href="{{ route('medical-certificate.preview', $medical_certificate->id) }}" target="_blank">Preview</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection