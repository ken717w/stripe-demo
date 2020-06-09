@extends('layouts.main')

@section('content')
    <div class="flex-center position-ref full-height">
        <div class="content">
            <div class="title m-b-md">
                {{ config('app.name') }}
            </div>

            <div class="links">
                <a href="{{ url('/cart') }}">Create Order</a>
                <a href="{{ url('/history') }}">View Order</a>
            </div>
        </div>
    </div>
@endsection
