@extends('admin.index')

@section('content')
<div class="card mt-4">
    <div class="card-header">
        <i class="fa-solid fa-book"></i>
        Appointment Records
    </div>
    <div class="card-body">
        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Patient Name</th>
                    <th>Appointment Date</th>
                    <th>Appointment Time</th>
                    <th>Status</th>
                    <th>Date Created</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>#</th>
                    <th>Patient Name</th>
                    <th>Appointment Date</th>
                    <th>Appointment Time</th>
                    <th>Status</th>
                    <th>Date Created</th>
                </tr>
            </tfoot>
            <tbody>
                @forelse($appointments as $key => $appointment)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $appointment->patient->full_name }}</td>
                        <td>{{ Carbon\Carbon::parse($appointment->appointment_date)->format('F d, Y') ?? 'N/A' }}</td>
                        <td>{{ $appointment->appointment_time ?? 'N/A' }}</td>
                        <td>{{ $appointment->status }}</td>
                        <td>{{ Carbon\Carbon::parse($appointment->created_at)->format('F d, Y H:i A') }}</td>
                        {{-- <td>
                            <div class="kebab-menu">
                                <div class="kebab-icon">⋮</div>
                                    <div class="menu-options">
                                        <a href="{{ route('patient.show', ['id' => $patient->id]) }}">View</a>
                                        <a href="{{ route('patient.checkup', ['id' => $patient->id]) }}">Add Checkup</a>
                                        <a href="{{ route('patient.edit', ['id' => $patient->id]) }}">Edit</a>
                                        <a href="{{ route('admin.create') }}">Deactivate</a>
                                        <a href="{{ route('admin.create') }}">Delete</a>
                                </div>
                            </div>
                        </td> --}}
                    </tr>
                @empty
                @endforelse
            </tbody>
        </table>
@endsection
