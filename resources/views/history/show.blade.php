@extends('layouts.main')

@section('title', 'View Order')

@section('content')
    <div class="flex-center position-ref full-height">
        <div class="content">
            <h1 class="mb-5 text-center">@yield('title')</h1>

            <p class="lead">Your order details:</p>

            <dl>
                <dt>Customer Name</dt>
                <dd>{{ $customerName }}</dd>
                <dt>Customer Phone</dt>
                <dd>{{ $customerPhone }}</dd>
                <dt>Customer Email</dt>
                <dd>{{ $customerEmail }}</dd>
                <dt>Order Currency</dt>
                <dd>{{ $orderCurrency }}</dd>
                <dt>Order Price</dt>
                <dd>{{ $orderPrice }}</dd>
            </dl>

            <div class="links">
                <a href="{{ url('/cart') }}">Create Order</a>
                <a href="{{ url('/history') }}">View Order</a>
            </div>
        </div>
    </div>
@endsection
