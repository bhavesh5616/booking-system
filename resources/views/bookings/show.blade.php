@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg p-4">
        <h2 class="text-center mb-4">Booking Details</h2>

        <div class="mb-3">
            <strong>Customer Name:</strong> {{ $booking->customer_name }}
        </div>

        <div class="mb-3">
            <strong>Customer Email:</strong> {{ $booking->customer_email }}
        </div>

        <div class="mb-3">
            <strong>Booking Date:</strong> {{ date('d M Y', strtotime($booking->booking_date)) }}
        </div>

        <div class="mb-3">
            <strong>Booking Type:</strong> {{ $booking->booking_type }}
        </div>

        @if($booking->booking_type == 'Half Day')
            <div class="mb-3">
                <strong>Booking Slot:</strong> {{ $booking->booking_slot }}
            </div>
        @endif

        @if($booking->booking_type == 'Custom')
            <div class="mb-3">
                <strong>Booking Time:</strong> {{ date('h:i A', strtotime($booking->booking_from)) }} - {{ date('h:i A', strtotime($booking->booking_to)) }}
            </div>
        @endif

        <div class="text-center">
            <a href="{{ route('bookings.index') }}" class="btn btn-secondary">Back to List</a>
            <a href="{{ route('bookings.edit', $booking->id) }}" class="btn btn-primary">Edit</a>
        </div>
    </div>
</div>
@endsection
