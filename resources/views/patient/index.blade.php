@extends('admin.index')

@section('content')
<div class="card mt-4">
    <div class="card-header">
        <i class="fas fa-table me-1"></i>
        Patient Records
    </div>
    <div class="card-body">
        <a class="btn btn-primary" href="{{ route('patient.create') }}">New Patient</a>
        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Civil Status</th>
                    <th>Address</th>
                    <th>Occupation</th>
                    <th>Contact No.</th>
                    <th>Birthday</th>
                    <th></th>
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
                    <th></th>
                </tr>
            </tfoot>
            <tbody>
                @forelse($patients as $patient)
                    <tr>
                        <td>{{ $patient->full_name }}</td>
                        <td>{{ $patient->age ?? 'N/A' }}</td>
                        <td>{{ $patient->civil_status ?? 'N/A' }}</td>
                        <td>{{ $patient->full_address }}</td>
                        <td>{{ $patient->occupation }}</td>
                        <td>{{ $patient->contact_no }}</td>
                        <td>{{ $patient->birthday }}</td>
                        <td>
                            <a class="btn btn-primary" href="{{ route('patient.checkup', ['id' => $patient->id]) }}">Add Checkup</a>
                            <a class="btn btn-primary" href="{{ route('patient.edit', ['id' => $patient->id]) }}">Edit</a>
                            <a class="btn btn-primary" href="{{ route('admin.create') }}">Deactivate</a>
                            <a class="btn btn-primary" href="{{ route('admin.create') }}">Delete</a>
                        </td>
                    </tr>
                @empty
                @endforelse
            </tbody>
        </table?
@endsection