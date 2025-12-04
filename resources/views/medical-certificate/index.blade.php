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
                                <div class="d-flex gap-2">
                                    <a href="{{ route('medical-certificate.preview', $medical_certificate->id) }}"
                                       class="btn btn-sm btn-info"
                                       data-bs-toggle="tooltip"
                                       data-bs-placement="top"
                                       title="Preview Medical Certificate"
                                       target="_blank">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('medical-certificate.download', $medical_certificate->id) }}"
                                       class="btn btn-sm btn-primary"
                                       data-bs-toggle="tooltip"
                                       data-bs-placement="top"
                                       title="Download Medical Certificate">
                                        <i class="fa-solid fa-download"></i>
                                    </a>
                                    @if($medical_certificate->upload_pdf != '1')
                                        <a href="{{ route('medical-certificate.showUploadForm', $medical_certificate->id) }}"
                                           class="btn btn-sm btn-success"
                                           data-bs-toggle="tooltip"
                                           data-bs-placement="top"
                                           title="Upload Medical Certificate">
                                            <i class="fa-solid fa-upload"></i>
                                        </a>
                                    @endif
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