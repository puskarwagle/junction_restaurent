<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use App\Models\MenuItem;
use App\Providers\CartService;
use Illuminate\Support\Facades\Log;

class Cart extends Component
{
    public $cartItems = []; // Array to hold cart items
    public $subtotal = 0;
    public $shipping = 30; // Static shipping cost
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

protected function loadCart()
{
    $cart = $this->cartService->getCart();
    $this->cartItems = [];

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
        }
    }

    $this->calculateTotals();
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


    protected function calculateTotals()
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