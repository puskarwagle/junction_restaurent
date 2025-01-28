<div class="checkout-area pt-100 pb-70">
    <div class="container">
        <div class="row">
            <!-- Order Summary (Bill) -->
            <div class="col-lg-8">
                <div class="order-summary mb-4">
                    <h2>Order Summary</h2>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Particulars</th>
                                <th>Quantity</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cartItems as $item)
                                <tr>
                                    <td>{{ now()->format('Y-m-d') }}</td>
                                    <td>{{ $item['name'] }}</td>
                                    <td>{{ $item['quantity'] }}</td>
                                    <td>${{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                                </tr>
                            @endforeach
                            <!-- Totals Row -->
                            <tr>
                                <td colspan="3" class="text-end"><strong>Subtotal</strong></td>
                                <td>${{ number_format($subtotal, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Shipping</strong></td>
                                <td>${{ number_format($shipping, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total</strong></td>
                                <td>${{ number_format($total, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Shipping and Payment Form -->
            <div class="col-lg-4">
                <div class="checkout-form">
                    <h2>Shipping Information</h2>
                    <form wire:submit.prevent="processPayment">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="address" class="form-label">Shipping Address</label>
                            <input type="text" name="address" id="address" class="form-control" required>
                        </div>
                        <h2>Payment Information</h2>
                        <div class="form-group mb-3">
                            <label for="card_number" class="form-label">Card Number</label>
                            <input type="text" name="card_number" id="card_number" class="form-control" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="expiry_date" class="form-label">Expiry Date (MM/YY)</label>
                                    <input type="text" name="expiry_date" id="expiry_date" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cvv" class="form-label">CVV</label>
                                    <input type="text" name="cvv" id="cvv" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Place Order</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>