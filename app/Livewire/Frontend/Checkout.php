<?php

namespace App\Livewire\Frontend;

use Illuminate\Http\Request;
use App\Providers\CartService;
use App\Models\MenuItem;
use Livewire\Component;

class Checkout extends Component
{
    
    protected $cartService;

    public $subtotal, $shipping, $total;
    public $cartItems = [];

    public function mount()
    {
        $totals = session()->get('checkout_totals', [
            'subtotal' => 0,
            'shipping' => 0,
            'total' => 0,
        ]);
    
        $this->subtotal = $totals['subtotal'];
        $this->shipping = $totals['shipping'];
        $this->total = $totals['total'];

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

    public function __construct()
    {
        $this->cartService = new CartService();
    }

    public function index()
    {
        // Fetch cart items from the session
        $cartItems = $this->cartService->getCart();

        // Calculate totals
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item['price'] * $item['quantity'];
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
        $this->cartService->clearCart();

        // Redirect to the success page
        return redirect()->route('checkout.success')->with('success', 'Payment processed successfully!');
    }

    public function render()
    {
        return view('frontend.checkout');
    }

    public function success()
    {
        return view('checkout-success');
    }
}