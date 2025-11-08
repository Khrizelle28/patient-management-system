@extends('admin.index')

@section('content')
    <div class="card mt-4">
        <div class="card-header">
            <i class="fa-solid fa-receipt"></i>
            Order Records
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

            <table id="datatablesSimple" class="tableOrder">
                <thead>
                    <tr>
                        <th>Order Number</th>
                        <th>Patient Name</th>
                        <th>Total Amount</th>
                        <th>Items Count</th>
                        <th>Status</th>
                        <th>Order Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Order Number</th>
                        <th>Patient Name</th>
                        <th>Total Amount</th>
                        <th>Items Count</th>
                        <th>Status</th>
                        <th>Order Date</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
                <tbody>
                    @forelse($orders ?? [] as $order)
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->patientUser->full_name ?? 'N/A' }}</td>
                            <td>₱{{ number_format($order->total_amount, 2) }}</td>
                            <td>{{ $order->items->count() }}</td>
                            <td>
                                @if($order->status === 'ready to pickup')
                                    <span class="badge bg-info">Ready to Pickup</span>
                                @elseif($order->status === 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                                @endif
                            </td>
                            <td>{{ Carbon\Carbon::parse($order->created_at)->format('F d, Y h:i A') }}</td>
                            <td>
                                <div class="kebab-menu">
                                    <div class="kebab-icon">⋮</div>
                                    <div class="menu-options">
                                        <a href="{{ route('order.show', ['id' => $order->id]) }}">View Details</a>
                                        @if($order->status === 'ready to pickup')
                                            <a href="{{ route('order.update-status', ['id' => $order->id, 'status' => 'completed']) }}"
                                               onclick="return confirm('Mark this order as completed?')">
                                                Mark as Completed
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
