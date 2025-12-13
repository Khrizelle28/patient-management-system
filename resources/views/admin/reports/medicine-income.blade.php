@extends('admin.index')

@section('content')
    <div class="card mt-4">
        <div class="card-header">
            <i class="fa-solid fa-pills"></i>
            Medicine Income Report
        </div>
        <div class="card-body">
            <form action="{{ route('medicine-income.index') }}" method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label for="medicine_from_date" class="form-label">From Date</label>
                        <input type="date" class="form-control" id="medicine_from_date" name="medicine_from_date" value="{{ request('medicine_from_date') }}">
                    </div>
                    <div class="col-md-5">
                        <label for="medicine_to_date" class="form-label">To Date</label>
                        <input type="date" class="form-control" id="medicine_to_date" name="medicine_to_date" value="{{ request('medicine_to_date') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label d-block">&nbsp;</label>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-search"></i> Search
                        </button>
                        <a href="{{ route('medicine-income.index') }}" class="btn btn-secondary">
                            <i class="fa-solid fa-refresh"></i> Reset
                        </a>
                    </div>
                </div>
            </form>

            <table id="datatablesSimple" class="table table-striped">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Patient</th>
                        <th>Medicines Ordered</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($medicineIncomes ?? [] as $income)
                        <tr>
                            <td>{{ $income['order_number'] }}</td>
                            <td>{{ \Carbon\Carbon::parse($income['date'])->format('F d, Y') }}</td>
                            <td>{{ $income['patient'] }}</td>
                            <td>
                                @foreach($income['items'] as $item)
                                    {{ $item['medicine'] }}<br>
                                @endforeach
                            </td>
                            <td>
                                @foreach($income['items'] as $item)
                                    {{ $item['quantity'] }}<br>
                                @endforeach
                            </td>
                            <td>
                                @foreach($income['items'] as $item)
                                    {{ number_format($item['subtotal'], 0) }}<br>
                                @endforeach
                            </td>
                            <td>{{ number_format($income['total_amount'], 0) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No medicine sales found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3 p-3 bg-light border">
                <div class="d-flex justify-content-between">
                    <strong>Total Medicine Income:</strong>
                    <strong>₱{{ number_format($totalMedicineIncome ?? 0, 2) }}</strong>
                </div>
            </div>
        </div>
    </div>
@endsection
