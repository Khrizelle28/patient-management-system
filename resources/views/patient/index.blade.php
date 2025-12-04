@extends('admin.index')

@section('content')
<div class="card mt-4">
    <div class="card-header">
        <i class="fa-solid fa-book"></i>
        Patient Records
    </div>
    <div class="card-body">
        <a class="btn btn-primary"
           href="{{ route('patient.create') }}"
           data-bs-toggle="tooltip"
           data-bs-placement="top"
           title="Create New Patient Record">New Patient</a>
        <table id="datatablesSimple" class="tablePatient">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Civil Status</th>
                    <th>Address</th>
                    <th>Occupation</th>
                    <th>Contact No.</th>
                    <th>Birthday</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Civil Status</th>
                    <th>Address</th>
                    <th>Occupation</th>
                    <th>Contact No.</th>
                    <th>Birthday</th>
                     <th>Actions</th>
                </tr>
            </tfoot>
            <tbody>
                @forelse($patients as $patient)
                    <tr onclick="window.location='{{ route('patient.checkup', ['id' => $patient->id]) }}'" style="cursor:pointer;">
                        <td>{{ $patient->full_name }}</td>
                        <td>{{ $patient->age ?? 'N/A' }}</td>
                        <td>{{ $patient->civil_status ?? 'N/A' }}</td>
                        <td>{{ $patient->full_address }}</td>
                        <td>{{ $patient->occupation }}</td>
                        <td>{{ $patient->contact_no }}</td>
                        <td>{{ $patient->birthday }}</td>
                        <td onclick="event.stopPropagation();">
                            <div class="d-flex gap-2">
                                <a href="{{ route('patient.show', ['id' => $patient->id]) }}"
                                   class="btn btn-sm btn-info"
                                   data-bs-toggle="tooltip"
                                   data-bs-placement="top"
                                   title="View Patient Details">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('patient.checkup', ['id' => $patient->id]) }}"
                                   class="btn btn-sm btn-success"
                                   data-bs-toggle="tooltip"
                                   data-bs-placement="top"
                                   title="Add Checkup">
                                    <i class="fa-solid fa-stethoscope"></i>
                                </a>
                                <a href="{{ route('patient.edit', ['id' => $patient->id]) }}"
                                   class="btn btn-sm btn-primary"
                                   data-bs-toggle="tooltip"
                                   data-bs-placement="top"
                                   title="Edit Patient">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
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
