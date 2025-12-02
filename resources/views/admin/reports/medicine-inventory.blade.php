@extends('admin.index')

@section('content')
    <div class="card mt-4">
        <div class="card-header">
            <i class="fa-solid fa-chart-line"></i>
            Medicine Inventory Report
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table table-striped">
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
                            <td>{{ Carbon\Carbon::parse($product->expiration_date)->format('F d, Y') ?? 'N/A' }}</td>
                            <td>
                                @foreach($product->statuses as $status)
                                    <span class="badge bg-{{ $status['class'] }} me-1">
                                        {{ $status['label'] }}
                                    </span>
                                @endforeach
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
@endsection
