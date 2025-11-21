@extends('admin.index')

@section('content')
    @hasanyrole('Administrator|Owner')
        <div class="card mt-4">
            <div class="card-header">
                <i class="fa-solid fa-money-bill-trend-up"></i>
                Doctors Income Report
            </div>
            <div class="card-body">
                <form action="{{ route('dashboard') }}" method="GET" class="mb-4">
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
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
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
    @endhasanyrole

    @hasanyrole('Administrator|Owner')
        <div class="card mt-4">
            <div class="card-header">
                <i class="fa-solid fa-pills"></i>
                Medicine Income Report
            </div>
            <div class="card-body">
                <form action="{{ route('dashboard') }}" method="GET" class="mb-4">
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
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                <i class="fa-solid fa-refresh"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>

                <table id="datatablesMedicineIncome" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Date</th>
                            <th>Patient</th>
                            <th>Medicine/Meds Ordered</th>
                            <th>Qty</th>
                            <th>Total Income</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($medicineIncomes ?? [] as $income)
                            <tr>
                                <td>{{ $income['order_number'] }}</td>
                                <td>{{ \Carbon\Carbon::parse($income['date'])->format('F d, Y') }}</td>
                                <td>{{ $income['patient'] }}</td>
                                <td>{{ $income['medicines'] }}</td>
                                <td>{{ $income['total_quantity'] }}</td>
                                <td>₱{{ number_format($income['total_amount'], 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No medicine sales found</td>
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
    @endhasanyrole

    @hasanyrole('Doctor')
        <div class="card mt-4">
            <div class="card-header">
                <i class="fa-solid fa-money-bill-trend-up"></i>
                My Income Report
            </div>
            <div class="card-body">
                <table id="datatablesDoctorOwn" class="table table-striped">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Date of Consultation</th>
                            <th>Patient Name</th>
                            <th>Professional Fee</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($doctorOwnIncome ?? [] as $index => $income)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($income['date'])->format('F d, Y') }}</td>
                                <td>{{ $income['patient'] }}</td>
                                <td>₱{{ number_format($income['professional_fee'], 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No income records found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-3 p-3 bg-light border">
                    <div class="d-flex justify-content-between">
                        <strong>Total Professional Fee:</strong>
                        <strong>₱{{ number_format($totalDoctorOwnIncome ?? 0, 2) }}</strong>
                    </div>
                </div>
            </div>
        </div>
    @endhasanyrole

    @hasanyrole('Administrator|Owner|Medical Staff')
        <div class="card mt-4">
            <div class="card-header">
                <i class="fa-solid fa-chart-line"></i>
                Medicine Inventory Report
            </div>
            <div class="card-body">
                <table id="datatablesMedicine" class="table table-striped">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Medicine</th>
                            <th>Stock In</th>
                            <th>Stock Out</th>
                            <th>Remaining Stock</th>
                            <th>Unit Price</th>
                            <th>Expiry Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products ?? [] as $index => $product)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ number_format($product->overall_stock) }}</td>
                                <td>{{ number_format($product->quantity_sold) }}</td>
                                <td>{{ number_format($product->remaining_stock) }}</td>
                                <td>₱{{ number_format($product->price, 2) }}</td>
                                <td>{{ $product->expiration_date ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $product->status_class }}">
                                        {{ $product->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No products found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endhasanyrole
@endsection
