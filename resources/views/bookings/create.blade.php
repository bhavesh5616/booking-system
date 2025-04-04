@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg p-4">
        <h2 class="text-center mb-4">Book an Appointment</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('bookings.store') }}">
            @csrf

            <!-- Customer Name -->
            <div class="mb-3">
                <label for="customer_name" class="form-label">Customer Name:</label>
                <input type="text" name="customer_name" class="form-control" placeholder="Enter full name" required>
            </div>

            <!-- Customer Email -->
            <div class="mb-3">
                <label for="customer_email" class="form-label">Customer Email:</label>
                <input type="email" name="customer_email" class="form-control" placeholder="Enter email address" required>
            </div>

            <!-- Booking Date -->
            <div class="mb-3">
                <label for="booking_date" class="form-label">Booking Date:</label>
                <input type="date" name="booking_date" class="form-control" required>
            </div>

            <!-- Booking Type -->
            <div class="mb-3">
                <label for="booking_type" class="form-label">Booking Type:</label>
                <select name="booking_type" id="booking_type" class="form-select" required>
                    <option value="Full Day">Full Day</option>
                    <option value="Half Day">Half Day</option>
                    <option value="Custom">Custom</option>
                </select>
            </div>

            <!-- Booking Slot (Only for Half Day) -->
            <div class="mb-3" id="slot_section" style="display: none;">
                <label for="booking_slot" class="form-label">Booking Slot:</label>
                <select name="booking_slot" class="form-select">
                    <option value="First Half">First Half</option>
                    <option value="Second Half">Second Half</option>
                </select>
            </div>

            <!-- Booking Time (Only for Custom) -->
            <div id="time_section" style="display: none;">
                <div class="mb-3">
                    <label for="booking_from" class="form-label">From:</label>
                    <input type="time" name="booking_from" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="booking_to" class="form-label">To:</label>
                    <input type="time" name="booking_to" class="form-control">
                </div>
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary w-100">Book Now</button>
            </div>
        </form>
    </div>
</div>

<!-- Script to Handle Booking Type Changes -->
<script>
    document.getElementById('booking_type').addEventListener('change', function () {
        let slotSection = document.getElementById('slot_section');
        let timeSection = document.getElementById('time_section');

        slotSection.style.display = (this.value === 'Half Day') ? 'block' : 'none';
        timeSection.style.display = (this.value === 'Custom') ? 'block' : 'none';
    });
</script>

<!-- Bootstrap CSS (Ensure you have Bootstrap included in your project) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
@endsection
