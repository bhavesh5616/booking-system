@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg p-4">
        <h2 class="text-center mb-4">Bookings List</h2>

        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Add Button & Search Bar -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('bookings.create') }}" class="btn btn-success">
                + Add Booking
            </a>
            <input type="text" id="search" class="form-control w-25" 
                placeholder="Search by name or email">
        </div>

        <!-- Bookings Table -->
        <div class="table-responsive" id="table-container">
            @include('bookings.partials.table', ['bookings' => $bookings])
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $bookings->links() }}
        </div>
    </div>
</div>

<!-- Include jQuery for AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    $('#search').on('input', function () {
        let search = $(this).val();
        $.ajax({
            url: "{{ route('bookings.index') }}",
            method: "GET",
            data: { search: search },
            success: function (response) {
                $('#table-container').html(response.table); // Update table dynamically
            }
        });
    });
});
</script>

@endsection
