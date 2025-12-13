# Order Confirmation Email

This email template is used to send order confirmation notifications to customers after successful payment.

## Usage Example

```php
use App\Mail\OrderConfirmationMail;
use Illuminate\Support\Facades\Mail;

// Prepare the order data
$orderData = [
    'customer_name' => 'John Doe',
    'order_number' => '00123',
    'order_date' => 'December 11, 2025',
    'payment_method' => 'PayPal',
    'order_status' => 'Ready to Pickup',
    'items' => [
        [
            'name' => 'Biogesic',
            'description' => 'Pain reliever',
            'quantity' => 2,
            'total' => '₱240.00'
        ],
        [
            'name' => 'Neozep',
            'description' => 'Cold medicine',
            'quantity' => 4,
            'total' => '₱400.00'
        ],
    ],
    'subtotal' => '₱640.00',
    'discount' => '₱0.00', // Optional
    'tax' => '₱0.00', // Optional
    'total_amount' => '₱640.00',
    'pickup_name' => 'John Doe',
    'pickup_contact' => '+63 912 345 6789',
    'clinic_address' => 'Tejero St, Cebu City, Philippines',
    'order_tracking_url' => route('orders.track', ['id' => $orderId]),
    'clinic_phone' => '(032) 123-4567',
    'clinic_email' => 'support@tejeromedical.com',
];

// Send the email
Mail::to($customer->email)->send(new OrderConfirmationMail($orderData));
```

## Required Data Fields

- `customer_name` - Full name of the customer
- `order_number` - Order ID/reference number
- `order_date` - Formatted date of order
- `payment_method` - Payment method used (e.g., PayPal, Cash)
- `order_status` - Current status (e.g., Ready to Pickup, Processing)
- `items` - Array of ordered items with:
  - `name` - Item name
  - `quantity` - Quantity ordered
  - `total` - Total price for this item
  - `description` - Optional item description
- `subtotal` - Subtotal amount
- `total_amount` - Total order amount
- `pickup_name` - Name of person who will pickup
- `pickup_contact` - Contact number for pickup

## Optional Data Fields

- `discount` - Discount amount (if any)
- `tax` - Tax amount (if applicable)
- `clinic_address` - Pickup location address (defaults to 'Tejero Medical and Maternity Clinic')
- `order_tracking_url` - URL to track the order (defaults to '#')
- `clinic_phone` - Clinic contact phone (defaults to '(032) 123-4567')
- `clinic_email` - Support email (defaults to 'support@tejeromedical.com')

## Features

- Beautiful gradient design with pink/coral theme
- Success checkmark animation
- Responsive layout optimized for all devices
- Detailed order summary with grid layout
- Item listing with hover effects
- Pricing breakdown section
- Pickup information with icons
- Track order button
- Help section with contact methods
- Social media links
- Professional footer

## Integration with Payment Controller

You can integrate this into your payment flow:

```php
// In PaymentController.php, after order is confirmed
use App\Mail\OrderConfirmationMail;

// After updating order status
$orderData = [
    'customer_name' => $order->pickup_name,
    'order_number' => str_pad($order->id, 5, '0', STR_PAD_LEFT),
    'order_date' => now()->format('F d, Y'),
    'payment_method' => 'PayPal',
    'order_status' => 'Ready to Pickup',
    'items' => $order->items->map(function($item) {
        return [
            'name' => $item->product->name,
            'description' => $item->product->description ?? 'Medicine',
            'quantity' => $item->quantity,
            'total' => '₱'.number_format($item->subtotal, 2),
        ];
    })->toArray(),
    'subtotal' => '₱'.number_format($order->total_amount, 2),
    'total_amount' => '₱'.number_format($order->total_amount, 2),
    'pickup_name' => $order->pickup_name,
    'pickup_contact' => $order->contact_number,
    'order_tracking_url' => route('orders.show', $order->id),
];

Mail::to($order->patientUser->email)->send(new OrderConfirmationMail($orderData));
```

## Design Philosophy

This email template uses a vibrant, modern design with:
- Pink-to-coral gradient for a warm, welcoming feel
- Clear visual hierarchy
- Animated success indicator for positive reinforcement
- Easy-to-scan information layout
- Mobile-first responsive design
- High contrast for accessibility