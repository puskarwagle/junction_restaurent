<?php
namespace App\Livewire\Frontend;

use Illuminate\Http\Request;
use App\Models\MenuItem;
use Livewire\Component;

class Checkout extends Component
{
    public $subtotal, $shipping, $total;
    public $couponCode, $discount = 0, $error;
    public $cartItems = [];

    public function mount()
    {
        // dd(session()->all()); // This will show all session data

        $totals = session()->get('checkout_totals', [
            'subtotal' => 0,
            'shipping' => 0,
            'total' => 0,
        ]);

        $this->subtotal = $totals['subtotal'];
        $this->shipping = $totals['shipping'];
        $this->total = $totals['total'];

        // Fetch cart items directly from session
        $cart = session()->get('cart', []);
        foreach ($cart as $itemId => $quantity) {
            $item = MenuItem::find($itemId);
            if ($item) {
                $this->cartItems[] = [
                    'name' => $item->name,
                    'quantity' => $quantity,
                    'price' => $item->price,
                ];
            }
        }
    }

    public function applyCoupon()
    {
        $coupon = CouponCode::where('coupon', $this->couponCode)
            ->where('expiration_date', '>=', now())
            ->first();

        if ($coupon) {
            $this->discount = $coupon->amount;
            $this->error = null;
        } else {
            $this->error = 'Invalid or expired coupon.';
            $this->discount = 0;
        }
    }

    public function index()
    {
        // Fetch cart items directly from the session
        $cartItems = session()->get('cart', []);

        // Calculate totals
        $subtotal = 0;
        foreach ($cartItems as $itemId => $quantity) {
            $item = MenuItem::find($itemId);
            if ($item) {
                $subtotal += $item->price * $quantity;
            }
        }

        $shipping = 30; // Static shipping cost
        $total = $subtotal + $shipping;

        return view('checkout', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $total,
        ]);
    }

    public function processPayment(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:255',
            'card_number' => 'required|string|max:16',
            'expiry_date' => 'required|string|max:5',
            'cvv' => 'required|string|max:3',
        ]);

        // Process payment (this is a placeholder; integrate with a payment gateway like Stripe)
        // For now, we'll just clear the cart and redirect to a success page.

        // Clear the cart
        session()->forget('cart');

        // Redirect to the success page
        return redirect()->route('checkout.delivery')->with('success', 'Payment processed successfully!');
    }

    public function render()
    {
        return view('livewire.pages.front.checkout.checkout');
    }

    public function delivery()
    {
        return view('checkout-success');
    }
}
