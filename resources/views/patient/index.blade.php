@extends('admin.index')

@section('content')
<div class="card mt-4">
    <div class="card-header">
        <i class="fa-solid fa-book"></i>
        Patient Records
    </div>
    <div class="card-body">
        <div class="mb-3">
            <a class="btn btn-primary"
               href="{{ route('patient.create') }}"
               data-bs-toggle="tooltip"
               data-bs-placement="top"
               title="Create New Patient Record">New Patient</a>
        </div>

        <div class="d-flex justify-content-center align-items-center gap-3 mb-3">
            <strong style="min-width: 70px;">FILTER:</strong>
            <select id="columnFilter" class="form-select" style="width: 180px;">
                <option value="">Select Column</option>
                <option value="0">Name</option>
                <option value="1">Age</option>
                <option value="2">Civil Status</option>
                <option value="3">Address</option>
                <option value="4">Occupation</option>
                <option value="5">Contact No.</option>
                <option value="6">Birthday</option>
            </select>
            <div style="width: 250px; position: relative; height: 38px;">
                <input type="text" id="filterValue" class="form-control" placeholder="Enter filter value" style="position: absolute; top: 0; left: 0; width: 100%; display: none;">
                <select id="filterValueSelect" class="form-select" style="position: absolute; top: 0; left: 0; width: 100%; display: none;"></select>
            </div>
            <button id="clearFilter" class="btn btn-secondary btn-sm" style="min-width: 60px; visibility: hidden;">Clear</button>
        </div>

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

        <div class="mt-3 p-3 bg-light border rounded">
            <div class="row">
                <div class="col-md-6">
                    <strong>Total Patients:</strong> <span id="totalPatients">{{ $patients->count() }}</span>
                </div>
                <div class="col-md-6 text-end">
                    <strong>Filtered Results:</strong> <span id="filteredPatients">{{ $patients->count() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let patientDataTable = null;
    let currentColumnIndex = null;

    const civilStatusValues = ['SINGLE', 'MARRIED', 'ANNULLED', 'SEPARATED', 'WIDOWED'];

    window.addEventListener('DOMContentLoaded', function() {
        // Wait for DataTable initialization
        setTimeout(() => {
            const tableElement = document.getElementById('datatablesSimple');
            const columnFilter = document.getElementById('columnFilter');
            const filterValue = document.getElementById('filterValue');
            const filterValueSelect = document.getElementById('filterValueSelect');
            const clearFilterBtn = document.getElementById('clearFilter');

            if (tableElement) {
                // Column selection change
                columnFilter.addEventListener('change', function() {
                    const columnIndex = this.value;
                    currentColumnIndex = columnIndex;

                    // Hide both input types initially
                    filterValue.style.display = 'none';
                    filterValueSelect.style.display = 'none';
                    clearFilterBtn.style.visibility = 'hidden';

                    if (columnIndex === '') {
                        filterTable('', '');
                        return;
                    }

                    // Show appropriate input based on column
                    if (columnIndex === '2') { // Civil Status column
                        filterValueSelect.innerHTML = '<option value="">Select Status</option>';
                        civilStatusValues.forEach(status => {
                            const option = document.createElement('option');
                            option.value = status;
                            option.textContent = status.charAt(0) + status.slice(1).toLowerCase();
                            filterValueSelect.appendChild(option);
                        });
                        filterValueSelect.style.display = 'block';
                    } else {
                        filterValue.value = '';
                        filterValue.style.display = 'block';
                    }

                    clearFilterBtn.style.visibility = 'visible';
                });

                // Text input filter
                filterValue.addEventListener('input', function() {
                    if (currentColumnIndex) {
                        filterTable(currentColumnIndex, this.value);
                    }
                });

                // Select input filter
                filterValueSelect.addEventListener('change', function() {
                    if (currentColumnIndex) {
                        filterTable(currentColumnIndex, this.value);
                    }
                });

                // Clear filter button
                clearFilterBtn.addEventListener('click', function() {
                    columnFilter.value = '';
                    filterValue.value = '';
                    filterValueSelect.value = '';
                    filterValue.style.display = 'none';
                    filterValueSelect.style.display = 'none';
                    clearFilterBtn.style.visibility = 'hidden';
                    currentColumnIndex = null;
                    filterTable('', '');
                });

                // Monitor for changes to update count
                const observer = new MutationObserver(function() {
                    updateFilteredCount();
                });

                const tbody = tableElement.querySelector('tbody');
                if (tbody) {
                    observer.observe(tbody, {
                        childList: true,
                        subtree: true,
                        attributes: true,
                        attributeFilter: ['style']
                    });
                }

                // Initial count
                updateFilteredCount();
            }
        }, 600);
    });

    function filterTable(columnIndex, filterText) {
        const rows = document.querySelectorAll('#datatablesSimple tbody tr');

        rows.forEach(row => {
            if (!columnIndex || !filterText) {
                row.style.display = '';
            } else {
                const cell = row.cells[parseInt(columnIndex)];
                if (cell) {
                    const cellText = cell.textContent.trim().toUpperCase();
                    const searchText = filterText.toUpperCase();
                    row.style.display = cellText.includes(searchText) ? '' : 'none';
                }
            }
        });

        updateFilteredCount();
    }

    function updateFilteredCount() {
        const rows = document.querySelectorAll('#datatablesSimple tbody tr');
        let visibleCount = 0;

        rows.forEach(row => {
            if (row.style.display !== 'none' && !row.classList.contains('dataTable-empty')) {
                visibleCount++;
            }
        });

        const filteredElement = document.getElementById('filteredPatients');
        if (filteredElement) {
            filteredElement.textContent = visibleCount;
        }
    }
</script>
@endpush
@endsection
