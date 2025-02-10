<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use App\Models\MenuItem;
use App\Providers\CartService;
use Illuminate\Support\Facades\Log;

class Cart extends Component
{
    public $cartItems = [];
    public $subtotal = 0;
    public $shipping = 30;
    public $total = 0;

    protected $listeners = ['addItem' => 'addItem'];

    protected $cartService;

    public function __construct()
    {
        $this->cartService = new CartService();
    }

    public function mount()
{
    $this->loadCart();
}

public function loadCart()
{
    $cart = $this->cartService->getCart();
    $this->cartItems = [];
    $this->subtotal = 0;  // Initialize subtotal to 0
    $this->shipping = 30;  // Set static shipping cost
    
    foreach ($cart as $itemId => $quantity) {
        $item = MenuItem::find($itemId);
        if ($item) {
            $this->cartItems[$itemId] = [
                'id' => $item->id,
                'name' => $item->name,
                'price' => $item->price,
                'image_path' => $item->image_path,
                'quantity' => $quantity,
            ];

            // Calculate the subtotal
            $this->subtotal += $item->price * $quantity;
        }
    }

    // Calculate total (subtotal + shipping)
    $this->total = $this->subtotal + $this->shipping;

    // Store subtotal, shipping, and total in session
    session()->put('checkout_totals', [
        'subtotal' => $this->subtotal,
        'shipping' => $this->shipping,
        'total' => $this->total,
    ]);
}


public function addItem($itemId)
{
    $this->cartService->addItem($itemId);
    $this->loadCart();

    // Notify the Header component to update the cart count
    $this->dispatch('cartUpdated')->to(Header::class);
}

public function incrementQuantity($itemId)
{
    $this->cartService->updateQuantity($itemId, $this->cartItems[$itemId]['quantity'] + 1);
    $this->loadCart();
}

public function decrementQuantity($itemId)
{
    if ($this->cartItems[$itemId]['quantity'] > 1) {
        $this->cartService->updateQuantity($itemId, $this->cartItems[$itemId]['quantity'] - 1);
    } else {
        $this->cartService->removeItem($itemId);
    }
    $this->loadCart();
}

public function removeItem($itemId)
{
    $this->cartService->removeItem($itemId);
    $this->loadCart();
}


    public function calculateTotals()
    {
        $this->subtotal = 0;
        foreach ($this->cartItems as $item) {
            $this->subtotal += $item['price'] * $item['quantity'];
        }
        $this->total = $this->subtotal + $this->shipping;
    }

    public function render()
    {
        return view('livewire.pages.front.cart.cart');
    }
}