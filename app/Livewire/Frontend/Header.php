<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use Illuminate\Support\Facades\Log;

class Header extends Component
{
    public $cartCount = 0;

    protected $listeners = ['cart-updated' => 'updateCartCount'];

    public function mount()
    {
        try {
            // Initialize cart in session if not already set
            if (!session()->has('cart')) {
                session(['cart' => []]);
                Log::info('Cart session initialized.');
                $this->dispatch('console-log', message: 'Cart session initialized.');
            }

            $this->updateCartCount();
            Log::info('Header mounted. Cart count updated.');
            $this->dispatch('console-log', message: 'Header mounted. Cart count updated.');
        } catch (\Exception $e) {
            Log::error('Failed to mount Header: ' . $e->getMessage());
            $this->dispatch('console-log', message: 'Error: Failed to mount Header.');
        }
    }

    public function updateCartCount()
    {
        try {
            $cart = session('cart', []);
            $this->cartCount = count($cart);
            Log::info('Cart count updated to: ' . $this->cartCount . ', Cart Items: ' . json_encode($cart));
            $this->dispatch('console-log', message: 'Cart count updated to: ' . $this->cartCount . ', Cart Items: ' . json_encode($cart));
        } catch (\Exception $e) {
            Log::error('Failed to update cart count: ' . $e->getMessage());
            $this->dispatch('console-log', message: 'Error: Failed to update cart count.');
        }
    }

    public function render()
    {
        return view('livewire.pages.front.header.header');
    }
}