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
                    <th>Actions</th>
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
                    <th>Actions</th>
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
                        <td>
                            <div class="kebab-menu">
                                <div class="kebab-icon">⋮</div>
                                <div class="menu-options">
                                    <a href="#" onclick="event.preventDefault();">View</a>
                                    <a href="javascript:void(0);" onclick="openMedCertModal({{ $appointment->id }})">Generate MedCert</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No appointments found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modals for Medical Certificate -->
@foreach($appointments as $appointment)
    <div class="modal fade" id="medCertModal{{ $appointment->id }}" tabindex="-1" role="dialog" aria-labelledby="medCertModalLabel{{ $appointment->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="medCertModalLabel{{ $appointment->id }}">Generate Medical Certificate</h5>
                    <button type="button" class="close" onclick="closeMedCertModal({{ $appointment->id }})" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="medCertForm{{ $appointment->id }}" action="{{ route('appointment.medical-certificate', $appointment->id) }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="purpose{{ $appointment->id }}">Purpose:</label>
                            <select class="form-control" id="purpose{{ $appointment->id }}" name="purpose" required>
                                <option value disabled selected>Select Purpose</option>
                                <option value="Sick Leave">Sick Leave</option>
                                <option value="Medical Documentation">Medical benefits</option>
                                <option value="Fit to Travel">Fit to Travel</option>
                                <option value="Insurance Claims">Insurance Claims</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="medicalCondition{{ $appointment->id }}">Medical Condition</label>
                            <input type="text" class="form-control" id="medicalCondition{{ $appointment->id }}" name="medical_condition" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="remarks{{ $appointment->id }}">Remarks</label>
                            <textarea type="text" class="form-control" id="remarks{{ $appointment->id }}" name="remarks" rows="3" style="resize: none;"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeMedCertModal({{ $appointment->id }})">Cancel</button>
                    <button type="submit" form="medCertForm{{ $appointment->id }}" class="btn btn-primary">Generate</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

@endsection

@push('scripts')
<script>
function openMedCertModal(appointmentId) {
    $('#medCertModal' + appointmentId).modal('show');
}

function closeMedCertModal(appointmentId) {
    $('#medCertModal' + appointmentId).modal('hide');
    $('.modal-backdrop').remove();
    $('body').removeClass('modal-open');
    $('body').css('padding-right', '');
}
</script>
@endpush