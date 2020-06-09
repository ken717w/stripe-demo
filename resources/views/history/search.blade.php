@extends('layouts.main')

@section('title', 'View Order')

@section('content')
    <div class="container">
        <h1 class="mb-5 text-center">@yield('title')</h1>

        <form class="form" action="" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 offset-md-3">
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
                <div class="col-md-6 offset-md-3">
                    <h2>Order Information</h3>
                    <div class="form-group">
                        <label for="customer-email">Customer Email</label>
                        <input type="email" class="form-control" name="customerEmail" required id="customer-email">
                    </div>
                    <div class="form-group">
                        <label for="payment-ref">Payment Reference Number</label>
                        <input type="text" class="form-control" name="paymentRef" required id="payment-ref">
                    </div>
                </div>
            </div>
            <div class="form-group text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
@endsection
