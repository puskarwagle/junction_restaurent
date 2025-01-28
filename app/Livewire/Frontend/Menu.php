<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use App\Models\MenuItem;
use App\Providers\CartService;
use Illuminate\Support\Facades\Log;


class Menu extends Component
{
    protected $cartService;
    public $selectedItems = [];

    public function __construct()
    {
        $this->cartService = new CartService();
    }

    public function mount()
    {
        // Initialize selectedItems with the current cart items
        $this->initSelectedItems();
    }
    
    public function initSelectedItems()
    {
        $cart = $this->cartService->getCart();
        $this->selectedItems = array_keys($cart);
    }

    public function addItem($itemId)
    {
        try {
            // Add the item to the cart using the CartService
            $this->cartService->addItem($itemId);
    
            // Add the item to the selectedItems array
            if (!in_array($itemId, $this->selectedItems)) {
                $this->selectedItems[] = $itemId;
            }
    
            // Dispatch the cart-updated event to update the cart count in the Header component
            $this->dispatch('cart-updated');
    
            Log::info('Item added to cart. Dispatched event with itemId: ' . $itemId);
            $this->dispatch('console-log', message: 'Item added to cart. Dispatched event with itemId: ' . $itemId);
        } catch (\Exception $e) {
            Log::error('Failed to add item to cart: ' . $e->getMessage());
            $this->dispatch('console-log', message: 'Error: Failed to add item to cart.');
        }
    }

    public function removeItem($itemId)
    {
        try {
            $this->cartService->removeItem($itemId);
    
            // Remove the item from the selectedItems array
            $this->selectedItems = array_filter($this->selectedItems, function ($id) use ($itemId) {
                return $id != $itemId;
            });
    
            // Dispatch the cart-updated event to update the cart count in the Header component
            $this->dispatch('cart-updated');
    
            Log::info('Item removed from cart. ItemId: ' . $itemId);
            Log::info('Selected Items: ' . json_encode($this->selectedItems));
            $this->dispatch('console-log', message: 'Item removed from cart. ItemId: ' . $itemId);
        } catch (\Exception $e) {
            Log::error('Failed to remove item from cart: ' . $e->getMessage());
            $this->dispatch('console-log', message: 'Error: Failed to remove item from cart.');
        }
    }

        
    public function toggleItem($itemId)
    {
        if (in_array($itemId, $this->selectedItems)) {
            $this->removeItem($itemId);
        } else {
            $this->addItem($itemId);
        }
    }

    public function render()
    {
        $menuItems = MenuItem::select('id', 'name', 'price', 'image_path', 'description', 'type', 'discount', 'is_special')
            ->get()
            ->groupBy('type');
    
        return view('livewire.pages.front.menu.menu', [
            'menuItems' => $menuItems
        ]);
    }
}