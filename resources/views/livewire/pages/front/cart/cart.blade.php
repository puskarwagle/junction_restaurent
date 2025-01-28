<!-- Start Cart Area -->
<div class="cart-area pt-100 pb-70">
    <div class="container">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Productff</th>
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
                                        <img src="{{ asset($item['image_path']) }}" alt="images">
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

            <div class="coupon-code">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-6">
                        <form class="coupon">
                            <div class="mb-3">
                                <div class="coupon-group-form">
                                    <input type="text" class="form-control" placeholder="Coupon-Code">
                                    <button type="submit" class="default-btn">
                                        Apply Coupon<span></span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="bottom">
                            <a href="cart.html" class="default-btn">Update Cart<span></span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="checkout">
            <div class="checkout-working">
                <h2>Cart Totals</h2>
                <ul>
                    <li class="d-flex justify-content-between">
                        <span>Subtotal</span>
                        <span class="cart-number">${{ number_format($subtotal, 2) }}</span>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span>Shipping</span>
                        <span class="cart-number">${{ number_format($shipping, 2) }}</span>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span>Total</span>
                        <span class="cart-number-2">${{ number_format($total, 2) }}</span>
                    </li>
                </ul>
                <a href="checkout.html" class="default-btn">Proceed To Checkout<span></span></a>
            </div>
        </div>
    </div>
</div>
<!-- End Cart Area -->