@extends('admin.index')

@section('content')
    @hasanyrole('Administrator|Owner')
        <div class="card mt-4">
            <div class="card-header">
                <i class="fa-solid fa-money-bill-trend-up"></i>
                Doctors Income Report
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Doctor</th>
                            <th>Professional Fee</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Date</th>
                            <th>Doctor</th>
                            <th>Professional Fee</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @forelse($doctorIncomes ?? [] as $income)
                            <tr>
                                <td>{{ $income['date'] }}</td>
                                <td>{{ $income['doctor'] }}</td>
                                <td>₱{{ number_format($income['professional_fee'], 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">No income records found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endhasanyrole

    @hasanyrole('Administrator|Medical Staff|Owner')
        <div class="card mt-4">
            <div class="card-header">
                <i class="fa-solid fa-chart-line"></i>
                Medicine Inventory Report
            </div>
            <div class="card-body">
                <table id="datatablesMedicine" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Overall Stocks</th>
                            <th>Remaining Stocks</th>
                            <th>Expiration Date</th>
                            <th>Unit Price</th>
                            <th>Quantity Sold</th>
                            <th>Total Income</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Product Name</th>
                            <th>Overall Stocks</th>
                            <th>Remaining Stocks</th>
                            <th>Expiration Date</th>
                            <th>Unit Price</th>
                            <th>Quantity Sold</th>
                            <th>Total Income</th>
                            <th>Status</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @forelse($products ?? [] as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td>{{ number_format($product->overall_stock) }}</td>
                                <td>{{ number_format($product->remaining_stock) }}</td>
                                <td>{{ $product->expiration_date ?? 'N/A' }}</td>
                                <td>₱{{ number_format($product->price, 2) }}</td>
                                <td>{{ number_format($product->quantity_sold) }}</td>
                                <td>₱{{ number_format($product->total_income, 2) }}</td>
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
