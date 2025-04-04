<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::query();

        // Apply search filter if input exists
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('customer_name', 'LIKE', "%{$search}%")
                ->orWhere('customer_email', 'LIKE', "%{$search}%");
        }

        // Fetch and paginate results
        $bookings = $query->latest()->paginate(10);

        // Check if the request is AJAX, return JSON
        if ($request->ajax()) {
            return response()->json([
                'table' => view('bookings.partials.table', compact('bookings'))->render()
            ]);
        }

        return view('bookings.index', compact('bookings'));
    }


    public function create()
    {
        return view('bookings.create', ['slot' => null]); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name'     => 'required|string|max:255',
            'customer_email'    => 'required|email|max:255',
            'booking_date'      => 'required|date',
            'booking_type'      => 'required|in:Full Day,Half Day,Custom',
            'booking_slot'      => 'nullable|in:First Half,Second Half',
            'booking_from'      => 'nullable|required_if:booking_type,Custom|date_format:H:i',
            'booking_to'        => 'nullable|required_if:booking_type,Custom|date_format:H:i|after:booking_from',
        ]);
        // Validate duplicate bookings
        if ($this->isBookingConflict($request)) {
            throw ValidationException::withMessages(['booking_date' => 'This time slot is already booked.']);
        }

        Booking::create($request->all());
        return redirect()->route('bookings.index')->with('success', 'Booking successfully created.');

    }

    public function show(Booking $booking)
    {
        return view('bookings.show', compact('booking'));
    }


    public function edit(Booking $booking)
    {
        return view('bookings.edit', compact('booking'));
    }


    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'customer_name'     => 'required|string|max:255',
            'customer_email'    => 'required|email|max:255',
            'booking_date'      => 'required|date',
            'booking_type'      => 'required|in:Full Day,Half Day,Custom',
            'booking_slot'      => 'nullable|in:First Half,Second Half',
            'booking_from'      => 'nullable|required_if:booking_type,Custom|date_format:H:i',
            'booking_to'        => 'nullable|required_if:booking_type,Custom|date_format:H:i|after:booking_from',
        ]);

        if ($this->isBookingConflict($request, $booking->id)) {
            throw ValidationException::withMessages(['booking_date' => 'This time slot is already booked.']);
        }

        $booking->update($request->all());

        return redirect()->route('bookings.index')->with('success', 'Booking successfully updated.');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('bookings.index')->with('success', 'Booking successfully deleted.');
    }

    // private function isBookingConflict($request)
    // {
    //     $existingBookings = Booking::where('booking_date', $request->booking_date)->get();

    //     foreach ($existingBookings as $booking) {
    //         if ($booking->booking_type == 'Full Day') {
    //             return true; // No other bookings allowed on this date
    //         }

    //         if ($request->booking_type == 'Full Day') {
    //             return true; // Full-day booking not allowed if any slot is booked
    //         }

    //         if ($booking->booking_type == 'Half Day' && $booking->booking_slot == $request->booking_slot) {
    //             return true; // Same half-day slot already booked
    //         }

    //         if ($booking->booking_type == 'Custom' && $this->isTimeConflict($booking, $request)) {
    //             return true; // Custom time conflict
    //         }
    //     }

    //     return false;
    // }

    private function isBookingConflict($request, $ignoreId = null)
    {

        $existingBookings = Booking::where('booking_date', $request->booking_date)
            ->when($ignoreId, function ($query) use ($ignoreId) {
                return $query->where('id', '!=', $ignoreId);
            })
            ->get();

        foreach ($existingBookings as $booking) {
            if ($booking->booking_type == 'Full Day' || $request->booking_type == 'Full Day') {
                return true; 
            }

            if ($booking->booking_type == 'Half Day' && $booking->booking_slot == $request->booking_slot) {
                return true; 
            }

            if ($booking->booking_type == 'Custom' && $this->isTimeConflict($booking, $request)) {
                return true;
            }
        }

        return false;
    }



    private function isTimeConflict($existing, $request)
    {
        return (
            $request->booking_from < $existing->booking_to &&
            $request->booking_to > $existing->booking_from
        );
    }

}
