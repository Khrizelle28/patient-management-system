@extends('admin.index')

@section('content')
    <div class="card mt-4">
        <div class="card-header">
            <i class="fa-solid fa-chart-line"></i>
            Medicine Inventory Report
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

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
@endsection
