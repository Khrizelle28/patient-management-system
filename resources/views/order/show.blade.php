@extends('admin.index')

@section('content')
    <div class="card mt-4">
        <div class="card-header">
            <i class="fa-solid fa-file-invoice"></i>
            Order Details - {{ $order->order_number }}
        </div>
        <div class="card-body">
            <a href="{{ route('order.index') }}" class="btn btn-secondary mb-3">
                <i class="fa-solid fa-arrow-left"></i> Back to Orders
            </a>

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

            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Order Information</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">Order Number</th>
                            <td>{{ $order->order_number }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($order->status === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($order->status === 'processing')
                                    <span class="badge bg-info">Processing</span>
                                @elseif($order->status === 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @else
                                    <span class="badge bg-danger">Cancelled</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Order Date</th>
                            <td>{{ Carbon\Carbon::parse($order->created_at)->format('F d, Y h:i A') }}</td>
                        </tr>
                        <tr>
                            <th>Total Amount</th>
                            <td><strong>₱{{ number_format($order->total_amount, 2) }}</strong></td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <h5>Patient Information</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">Patient Name</th>
                            <td>{{ $order->patientUser->full_name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Contact Number</th>
                            <td>{{ $order->patientUser->contact_no ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Pickup Name</th>
                            <td>{{ $order->pickup_name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Order Contact Number</th>
                            <td>{{ $order->contact_number ?? 'N/A' }}</td>
                        </tr>
                        @if($order->notes)
                            <tr>
                                <th>Notes</th>
                                <td>{{ $order->notes }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>

            <div class="mb-3">
                @if($order->status === 'pending')
                    <a href="{{ route('order.update-status', ['id' => $order->id, 'status' => 'processing']) }}"
                       class="btn btn-info"
                       onclick="return confirm('Mark this order as processing?')">
                        <i class="fa-solid fa-spinner"></i> Mark as Processing
                    </a>
                    <a href="{{ route('order.update-status', ['id' => $order->id, 'status' => 'cancelled']) }}"
                       class="btn btn-danger"
                       onclick="return confirm('Cancel this order? Stock will be restored.')">
                        <i class="fa-solid fa-times"></i> Cancel Order
                    </a>
                @elseif($order->status === 'processing')
                    <a href="{{ route('order.update-status', ['id' => $order->id, 'status' => 'completed']) }}"
                       class="btn btn-success"
                       onclick="return confirm('Mark this order as completed?')">
                        <i class="fa-solid fa-check"></i> Mark as Completed
                    </a>
                @endif
            </div>

            <h5>Order Items</h5>
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Image</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $item->product->name ?? 'Product Deleted' }}</strong>
                                @if($item->product->description)
                                    <br><small class="text-muted">{{ $item->product->description }}</small>
                                @endif
                            </td>
                            <td>
                                @if($item->product->image)
                                    <img src="{{ $item->product->image }}" height="50" width="50" alt="{{ $item->product->name }}">
                                @else
                                    <img src="/images/default-product.png" height="50" width="50" alt="No image">
                                @endif
                            </td>
                            <td>₱{{ number_format($item->price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>₱{{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr class="table-light">
                        <td colspan="5" class="text-end"><strong>Total:</strong></td>
                        <td><strong>₱{{ number_format($order->total_amount, 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
