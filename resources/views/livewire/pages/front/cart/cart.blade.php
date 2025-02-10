<!-- Start Cart Area -->
<div class="cart-area pt-100 pb-70">
    <div class="container">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Products</th>
                        <th scope="col" class="cart-text">Name</th>
                        <th scope="col">Unit Price</th>
                        <th scope="col" class="cart-quantity">Quantity</th>
                        <th scope="col">Total</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cartItems as $item)
                        <tr wire:key="cart-item-{{ $item['id'] }}">
                            <th scope="col" class="table-img">
                                <div class="table-icon">
                                    <a href="shop-single.html">
                                    <img src="{{ asset($item['image_path']) }}"
                                        alt="images"
                                        style="width: 100px; height: 100px; object-fit: contain;">
                                    </a>
                                </div>
                            </th>
                            <th scope="col" class="cart-text">
                                <a href="cart.html">{{ $item['name'] }}</a>
                            </th>
                            <th scope="col">
                                ${{ number_format($item['price'], 2) }}
                            </th>
                            <th scope="col" class="cart-quantity">
                                <div class="pass-quantity">
                                    <div class="input-counter">
                                        <span class="minus-btn" wire:click="decrementQuantity({{ $item['id'] }})">
                                            <i class='bx bx-minus'></i>
                                        </span>
                                        <input type="text" value="{{ $item['quantity'] }}" readonly>
                                        <span class="plus-btn" wire:click="incrementQuantity({{ $item['id'] }})">
                                            <i class='bx bx-plus'></i>
                                        </span>
                                    </div>
                                </div>
                            </th>
                            <th scope="col">
                                ${{ number_format($item['price'] * $item['quantity'], 2) }}
                            </th>
                            <th>
                                <a href="#" class="delete-bin" wire:click="removeItem({{ $item['id'] }})">
                                    <img src="{{ asset('assets/images/delete-bin-icon.svg') }}" alt="images">
                                </a>
                            </th>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p>Subtotal: $<span wire:poll.500ms="calculateTotals">{{ number_format($subtotal, 2) }}</span></p>

            <button href="\checkout" wire:navigate class="btn btn-primary">Proceed to Checkout</button>

        </div>
    </div>
</div>
<!-- End Cart Area -->