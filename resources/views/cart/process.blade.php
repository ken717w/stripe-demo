@extends('layouts.main')

@section('title', 'Create Order')

@section('content')
    <div class="flex-center position-ref full-height">
        <div class="content">
            <h1 class="mb-5 text-center">@yield('title')</h1>

            <p class="lead">Your order is completed. Reference no: {{ $paymentRef }}</p>

            <div class="links">
                <a href="{{ url('/cart') }}">Create Order</a>
                <a href="{{ url('/history') }}">View Order</a>
            </div>
        </div>
    </div>
@endsection
