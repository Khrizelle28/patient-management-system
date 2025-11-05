<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of all orders.
     */
    public function index(): View
    {
        $orders = Order::query()
            ->with(['patientUser', 'items'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('order.index', compact('orders'));
    }

    /**
     * Display the specified order with its items.
     */
    public function show(string $id): View
    {
        $order = Order::query()
            ->with(['patientUser', 'items.product'])
            ->findOrFail($id);

        return view('order.show', compact('order'));
    }

    /**
     * Update the order status.
     */
    public function updateStatus(string $id, string $status): RedirectResponse
    {
        $order = Order::findOrFail($id);

        $validStatuses = ['pending', 'processing', 'completed', 'cancelled'];

        if (! in_array($status, $validStatuses)) {
            return redirect()->back()->with('error', 'Invalid status.');
        }

        // If cancelling, restore stock
        if ($status === 'cancelled' && $order->status !== 'cancelled') {
            DB::beginTransaction();

            try {
                foreach ($order->items as $orderItem) {
                    $orderItem->product->increment('stock', $orderItem->quantity);
                }

                $order->update(['status' => $status]);

                DB::commit();

                return redirect()->back()->with('success', 'Order cancelled and stock restored.');
            } catch (\Exception) {
                DB::rollBack();

                return redirect()->back()->with('error', 'Failed to cancel order.');
            }
        }

        $order->update(['status' => $status]);

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }
}
