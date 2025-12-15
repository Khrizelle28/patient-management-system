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
                    @php $rowNumber = 0; @endphp
                    @forelse($products ?? [] as $product)
                        @if($product->batches)
                            {{-- Product with batches --}}
                            @foreach($product->batches as $batchIndex => $batch)
                                @php $rowNumber++; @endphp
                                <tr>
                                    <td>{{ $rowNumber }}</td>
                                    <td>
                                        {{-- @if($batchIndex === 0) --}}
                                            <strong>{{ $product->name }}</strong>
                                        {{-- @endif --}}
                                    </td>
                                    <td>{{ number_format($batch->overall_stock) }}</td>
                                    <td>{{ number_format($batch->quantity_sold) }}</td>
                                    <td>
                                        {{ number_format($batch->remaining_stock) }}
                                        @if($batchIndex === 0 && $product->has_multiple_batches)
                                            <br><small class="text-muted">(Total: {{ number_format($product->batches->sum('remaining_stock')) }})</small>
                                        @endif
                                    </td>
                                    <td>
                                        {{-- @if($batchIndex === 0) --}}
                                            ₱{{ number_format($product->price, 2) }}
                                        {{-- @endif --}}
                                    </td>
                                    <td>{{ Carbon\Carbon::parse($batch->expiration_date)->format('F d, Y') }}</td>
                                    <td>
                                        @if($batchIndex === 0)
                                            @foreach($product->consolidated_statuses as $status)
                                                <span class="badge bg-{{ $status['class'] }} me-1">
                                                    {{ $status['label'] }}
                                                </span>
                                            @endforeach
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            {{-- Legacy product without batches --}}
                            @php $rowNumber++; @endphp
                            <tr>
                                <td>{{ $rowNumber }}</td>
                                <td><strong>{{ $product->name }}</strong></td>
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
                        @endif
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No products found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pullout Section --}}
    {{-- @if($pullouts->isNotEmpty())
        <div class="card mt-4">
            <div class="card-header bg-danger text-white">
                <i class="fa-solid fa-exclamation-triangle"></i>
                Medicine Pullout - Near Expiry Items
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Medicine</th>
                            <th>Quantity</th>
                            <th>Expiration Date</th>
                            <th>Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pullouts as $index => $pullout)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $pullout['medicine'] }}</td>
                                <td>{{ number_format($pullout['quantity']) }}</td>
                                <td>{{ Carbon\Carbon::parse($pullout['expiration_date'])->format('F d, Y') }}</td>
                                <td><span class="badge bg-danger">{{ $pullout['reason'] }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif --}}
@endsection
