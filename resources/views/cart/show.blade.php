@extends('layouts.main')

@section('title', 'Create Order')

@section('content')
    <div class="container">
        <h1 class="mb-5 text-center">@yield('title')</h1>

        <form class="form" action="" method="POST">
            @csrf

            <div class="row">
                <div class="col-12">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                <div class="col-md-6">
                    <h2>Order Information</h3>
                    <div class="form-group">
                        <label for="customer-name">Customer Name</label>
                        <input type="text" class="form-control" name="customerName" required id="customer-name">
                    </div>
                    <div class="form-group">
                        <label for="customer-phone">Customer Phone</label>
                        <input type="text" class="form-control" name="customerPhone" required id="customer-phone">
                        <small class="form-text text-muted">For simplicity, only supports 8-digits phone numbers.</small>
                    </div>
                    <div class="form-group">
                        <label for="customer-email">Customer Email</label>
                        <input type="email" class="form-control" name="customerEmail" required id="customer-email">
                        <small class="form-text text-muted">Will be used to track orders.</small>
                    </div>
                    <div class="form-group">
                        <label for="order-currency">Currency</label>
                        <select class="custom-select" name="orderCurrency" required id="order-currency">
                            <option>HKD</option>
                            <option>USD</option>
                            <option>AUD</option>
                            <option>EUR</option>
                            <option>JPY</option>
                            <option>CNY</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="order-price">Price</label>
                        <input type="text" class="form-control" name="orderPrice" required id="order-price">
                        <small class="form-text text-muted">Must >= 1, up to 2 decimal places.</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <h2>Credit Card Information</h3>
                    <div class="form-group">
                        <label for="card-holder">Holder Name</label>
                        <input type="text" class="form-control" name="cardHolder" required id="card-holder">
                    </div>
                    <div class="form-group">
                        <label for="card-number">Card Number</label>
                        <input type="text" class="form-control" name="cardNumber" required id="card-number">
                        <small class="form-text text-muted">14 - 16 digits.</small>
                    </div>
                    <div class="form-row">
                        <div class="form-group col">
                            <label for="card-expiry-month">Expiry Date (Month)</label>
                            <input type="text" class="form-control" name="cardExpiryMonth" required id="card-expiry-month">
                            <small class="form-text text-muted">2 digits, e.g. 06.</small>
                        </div>
                        <div class="form-group col">
                            <label for="card-expiry-year">Expiry Date (Year)</label>
                            <input type="text" class="form-control" name="cardExpiryYear" required id="card-expiry-year">
                            <small class="form-text text-muted">2 digits, e.g. 20.</small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="card-cvc">CVC/CVV</label>
                        <input type="text" class="form-control" name="cardCvc" required id="card-cvc">
                        <small class="form-text text-muted">3 - 4 digits.</small>
                    </div>
                </div>
            </div>
            <div class="form-group text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
@endsection
