@extends('admin.index')

@section('content')
    <div class="card mt-4">
        <div class="card-header">
            <i class="fa-solid fa-money-bill-trend-up"></i>
            Doctors Income Report
        </div>
        <div class="card-body">
            <form action="{{ route('doctor-income.index') }}" method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label for="from_date" class="form-label">From Date</label>
                        <input type="date" class="form-control" id="from_date" name="from_date" value="{{ request('from_date') }}">
                    </div>
                    <div class="col-md-5">
                        <label for="to_date" class="form-label">To Date</label>
                        <input type="date" class="form-control" id="to_date" name="to_date" value="{{ request('to_date') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label d-block">&nbsp;</label>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-search"></i> Search
                        </button>
                        <a href="{{ route('doctor-income.index') }}" class="btn btn-secondary">
                            <i class="fa-solid fa-refresh"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
            <table id="datatablesSimple" class="table table-striped">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Date of Consultation</th>
                        <th>Doctor</th>
                        <th>Patient Name</th>
                        <th>Professional Fee</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($doctorIncomes ?? [] as $index => $income)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($income['date'])->format('F d, Y') }}</td>
                            <td>{{ $income['doctor'] }}</td>
                            <td>{{ $income['patient'] }}</td>
                            <td>₱{{ number_format($income['professional_fee'], 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No income records found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3 p-3 bg-light border">
                <div class="d-flex justify-content-between">
                    <strong>Total Professional Fee:</strong>
                    <strong>₱{{ number_format($totalProfessionalFee ?? 0, 2) }}</strong>
                </div>
            </div>
        </div>
    </div>
@endsection
