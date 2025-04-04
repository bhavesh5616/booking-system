<table class="table table-striped table-hover align-middle text-center">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Customer Name</th>
            <th>Email</th>
            <th>Booking Date</th>
            <th>Type</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($bookings as $index => $booking)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td class="text-start">{{ $booking->customer_name }}</td>
            <td>{{ $booking->customer_email }}</td>
            <td>{{ date('d M Y', strtotime($booking->booking_date)) }}</td>
            <td><span class="badge bg-primary">{{ $booking->booking_type }}</span></td>
            <td>
                <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-info btn-sm">
                    <i class="fas fa-eye"></i>
                </a>
                <a href="{{ route('bookings.edit', $booking->id) }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i>
                </a>
                <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center text-muted">No bookings found.</td>
        </tr>
        @endforelse
    </tbody>
</table>
