@extends('admin.index')

@section('content')
    @hasanyrole('Administrator|Owner')
        {{-- Statistics Cards --}}
        <div class="row mt-4">
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-white-75 small">Total Patients</div>
                                <div class="h2 mb-0">{{ number_format($totalPatients ?? 0) }}</div>
                            </div>
                            <div>
                                <i class="fas fa-users fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('patient.index') }}">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-white-75 small">Total Doctors</div>
                                <div class="h2 mb-0">{{ number_format($totalDoctors ?? 0) }}</div>
                            </div>
                            <div>
                                <i class="fas fa-user-md fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('admin.index') }}">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-white mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-white-75 small">Total Employees</div>
                                <div class="h2 mb-0">{{ number_format($totalEmployees ?? 0) }}</div>
                            </div>
                            <div>
                                <i class="fas fa-user-tie fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('admin.index') }}">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-danger text-white mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-white-75 small">Medicines Alert</div>
                                <div class="h2 mb-0">{{ number_format(($lowStockMedicines->count() ?? 0) + ($nearExpiryMedicines->count() ?? 0)) }}</div>
                            </div>
                            <div>
                                <i class="fas fa-exclamation-triangle fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#medicineAlerts">View Alerts</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fa-solid fa-money-bill-trend-up"></i>
                            Doctors Income Trend
                        </div>
                        <div class="text-end">
                            <small class="text-muted">Last 12 Months</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="doctorIncomeChart" style="max-height: 300px;"></canvas>
                        <div class="mt-3 p-3 bg-light border rounded">
                            <div class="d-flex justify-content-between">
                                <strong>Total (Last 12 Months):</strong>
                                <strong class="text-success">₱{{ number_format($totalDoctorIncome ?? 0, 2) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fa-solid fa-pills"></i>
                            Medicine Income Trend
                        </div>
                        <div class="text-end">
                            <small class="text-muted">Last 12 Months</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="medicineIncomeChart" style="max-height: 300px;"></canvas>
                        <div class="mt-3 p-3 bg-light border rounded">
                            <div class="d-flex justify-content-between">
                                <strong>Total (Last 12 Months):</strong>
                                <strong class="text-success">₱{{ number_format($totalMedicineIncome ?? 0, 2) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <i class="fa-solid fa-chart-bar"></i>
                        Combined Income Overview
                    </div>
                    <div class="card-body">
                        <canvas id="combinedIncomeChart" style="max-height: 350px;"></canvas>
                        <div class="mt-3 p-3 bg-light border rounded">
                            <div class="row text-center">
                                <div class="col-md-4">
                                    <h6 class="text-muted mb-1">Total Doctor Income</h6>
                                    <h4 class="text-primary mb-0">₱{{ number_format($totalDoctorIncome ?? 0, 2) }}</h4>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="text-muted mb-1">Total Medicine Income</h6>
                                    <h4 class="text-success mb-0">₱{{ number_format($totalMedicineIncome ?? 0, 2) }}</h4>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="text-muted mb-1">Combined Total</h6>
                                    <h4 class="text-info mb-0">₱{{ number_format(($totalDoctorIncome ?? 0) + ($totalMedicineIncome ?? 0), 2) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Medicine Alerts Section --}}
        <div class="row mt-4" id="medicineAlerts">
            <div class="col-md-6">
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <i class="fas fa-box-open"></i>
                        Low Stock Medicines
                    </div>
                    <div class="card-body">
                        @if($lowStockMedicines->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>Medicine Name</th>
                                            <th class="text-end">Stock</th>
                                            <th class="text-end">Unit Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($lowStockMedicines as $medicine)
                                            <tr>
                                                <td>{{ $medicine->name }}</td>
                                                <td class="text-end">
                                                    <span class="badge bg-danger">{{ number_format($medicine->stock) }}</span>
                                                </td>
                                                <td class="text-end">₱{{ number_format($medicine->price, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('product.index') }}" class="btn btn-sm btn-danger">
                                    <i class="fas fa-plus"></i> Restock Medicines
                                </a>
                            </div>
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-check-circle fa-3x mb-3"></i>
                                <p class="mb-0">All medicines are well stocked!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-warning">
                    <div class="card-header bg-warning text-white">
                        <i class="fas fa-calendar-times"></i>
                        Near Expiry Medicines
                    </div>
                    <div class="card-body">
                        @if($nearExpiryMedicines->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>Medicine Name</th>
                                            <th class="text-end">Stock</th>
                                            <th class="text-end">Expiry Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($nearExpiryMedicines as $medicine)
                                            <tr>
                                                <td>{{ $medicine->name }}</td>
                                                <td class="text-end">{{ number_format($medicine->stock) }}</td>
                                                <td class="text-end">
                                                    <span class="badge bg-warning">
                                                        {{ \Carbon\Carbon::parse($medicine->expiration_date)->format('M d, Y') }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('medicine-inventory.index') }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-list"></i> View Inventory
                                </a>
                            </div>
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-check-circle fa-3x mb-3"></i>
                                <p class="mb-0">No medicines expiring soon!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Doctor Income Chart
                const doctorCtx = document.getElementById('doctorIncomeChart').getContext('2d');
                new Chart(doctorCtx, {
                    type: 'line',
                    data: {
                        labels: @json($doctorIncomeChartData['labels'] ?? []),
                        datasets: [{
                            label: 'Doctor Income',
                            data: @json($doctorIncomeChartData['data'] ?? []),
                            borderColor: 'rgb(54, 162, 235)',
                            backgroundColor: 'rgba(54, 162, 235, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return '₱' + context.parsed.y.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return '₱' + value.toLocaleString('en-PH');
                                    }
                                }
                            }
                        }
                    }
                });

                // Medicine Income Chart
                const medicineCtx = document.getElementById('medicineIncomeChart').getContext('2d');
                new Chart(medicineCtx, {
                    type: 'line',
                    data: {
                        labels: @json($medicineIncomeChartData['labels'] ?? []),
                        datasets: [{
                            label: 'Medicine Income',
                            data: @json($medicineIncomeChartData['data'] ?? []),
                            borderColor: 'rgb(75, 192, 192)',
                            backgroundColor: 'rgba(75, 192, 192, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return '₱' + context.parsed.y.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return '₱' + value.toLocaleString('en-PH');
                                    }
                                }
                            }
                        }
                    }
                });

                // Combined Income Chart
                const combinedCtx = document.getElementById('combinedIncomeChart').getContext('2d');
                new Chart(combinedCtx, {
                    type: 'bar',
                    data: {
                        labels: @json($doctorIncomeChartData['labels'] ?? []),
                        datasets: [
                            {
                                label: 'Doctor Income',
                                data: @json($doctorIncomeChartData['data'] ?? []),
                                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                                borderColor: 'rgb(54, 162, 235)',
                                borderWidth: 1
                            },
                            {
                                label: 'Medicine Income',
                                data: @json($medicineIncomeChartData['data'] ?? []),
                                backgroundColor: 'rgba(75, 192, 192, 0.7)',
                                borderColor: 'rgb(75, 192, 192)',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': ₱' + context.parsed.y.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return '₱' + value.toLocaleString('en-PH');
                                    }
                                }
                            }
                        }
                    }
                });
            });
        </script>
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

    @hasanyrole('Medical Staff')
        {{-- Medicine Alerts for Medical Staff --}}
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <i class="fas fa-box-open"></i>
                        Low Stock Medicines
                    </div>
                    <div class="card-body">
                        @if($lowStockMedicines->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>Medicine Name</th>
                                            <th class="text-end">Stock</th>
                                            <th class="text-end">Unit Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($lowStockMedicines as $medicine)
                                            <tr>
                                                <td>{{ $medicine->name }}</td>
                                                <td class="text-end">
                                                    <span class="badge bg-danger">{{ number_format($medicine->stock) }}</span>
                                                </td>
                                                <td class="text-end">₱{{ number_format($medicine->price, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('product.index') }}" class="btn btn-sm btn-danger">
                                    <i class="fas fa-plus"></i> Restock Medicines
                                </a>
                            </div>
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-check-circle fa-3x mb-3"></i>
                                <p class="mb-0">All medicines are well stocked!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-warning">
                    <div class="card-header bg-warning text-white">
                        <i class="fas fa-calendar-times"></i>
                        Near Expiry Medicines
                    </div>
                    <div class="card-body">
                        @if($nearExpiryMedicines->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>Medicine Name</th>
                                            <th class="text-end">Stock</th>
                                            <th class="text-end">Expiry Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($nearExpiryMedicines as $medicine)
                                            <tr>
                                                <td>{{ $medicine->name }}</td>
                                                <td class="text-end">{{ number_format($medicine->stock) }}</td>
                                                <td class="text-end">
                                                    <span class="badge bg-warning">
                                                        {{ \Carbon\Carbon::parse($medicine->expiration_date)->format('M d, Y') }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('medicine-inventory.index') }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-list"></i> View Inventory
                                </a>
                            </div>
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-check-circle fa-3x mb-3"></i>
                                <p class="mb-0">No medicines expiring soon!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Latest Orders Section --}}
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <i class="fas fa-shopping-cart"></i>
                        Latest Medicine Orders
                    </div>
                    <div class="card-body">
                        @if($latestOrders->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Order #</th>
                                            <th>Patient</th>
                                            <th class="text-end">Total Amount</th>
                                            <th>Order Status</th>
                                            <th>Date</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($latestOrders as $order)
                                            <tr>
                                                <td><strong>{{ $order->order_number }}</strong></td>
                                                <td>{{ $order->patientUser->full_name ?? 'N/A' }}</td>
                                                <td class="text-end">₱{{ number_format($order->total_amount, 2) }}</td>
                                                <td>
                                                    @if($order->status === 'pending')
                                                        <span class="badge bg-warning">Pending</span>
                                                    @elseif($order->status === 'processing')
                                                        <span class="badge bg-info">Processing</span>
                                                    @elseif($order->status === 'completed')
                                                        <span class="badge bg-success">Completed</span>
                                                    @elseif($order->status === 'cancelled')
                                                        <span class="badge bg-danger">Cancelled</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y h:i A') }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('order.show', $order->id) }}" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('order.index') }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-list"></i> View All Orders
                                </a>
                            </div>
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                                <p class="mb-0">No orders yet!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endhasanyrole
@endsection
