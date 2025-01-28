<?php

namespace App\Providers;

class CartService
{
    protected $sessionKey = 'cart';

    public function __construct()
    {
        // Initialize the cart in the session if it doesn't exist
        if (!session()->has($this->sessionKey)) {
            session()->put($this->sessionKey, []);
        }
    }

    public function getCart()
    {
        return session($this->sessionKey, []);
    }

    public function addItem($itemId)
    {
        $cart = $this->getCart();
        if (isset($cart[$itemId])) {
            $cart[$itemId]++;
        } else {
            $cart[$itemId] = 1;
        }
        session()->put($this->sessionKey, $cart);
    }

    public function removeItem($itemId)
    {
        $cart = $this->getCart();
        if (isset($cart[$itemId])) {
            unset($cart[$itemId]);
            session()->put($this->sessionKey, $cart);
        }
    }

    public function updateQuantity($itemId, $quantity)
    {
        $cart = $this->getCart();
        if (isset($cart[$itemId])) {
            $cart[$itemId] = $quantity;
            session()->put($this->sessionKey, $cart);
        }
    }

    public function clearCart()
    {
        session()->forget($this->sessionKey);
    }
}