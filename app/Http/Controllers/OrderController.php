<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

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

        $validStatuses = ['ready to pickup', 'completed'];

        if (! in_array($status, $validStatuses)) {
            return redirect()->back()->with('error', 'Invalid status.');
        }

        $order->update(['status' => $status]);

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }

    /**
     * Preview order invoice template as PDF.
     */
    public function previewInvoice(): Response
    {
        $pdf = Pdf::loadView('pdf.order-invoice');
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('order-invoice.pdf');
    }
}
