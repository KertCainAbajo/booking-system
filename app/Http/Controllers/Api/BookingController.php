<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    /**
     * Get all bookings (paginated)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $perPage = $request->input('per_page', 15);

        // Customers see only their bookings
        if ($user->role && $user->role->name === 'customer') {
            $bookings = Booking::where('customer_id', $user->customer->id ?? null)
                ->with(['service', 'vehicle'])
                ->orderBy('booking_date', 'desc')
                ->paginate($perPage);
        } else {
            // Staff and admins see all bookings
            $bookings = Booking::with(['customer', 'service', 'vehicle', 'assignedStaff'])
                ->orderBy('booking_date', 'desc')
                ->paginate($perPage);
        }

        return response()->json([
            'success' => true,
            'data' => $bookings
        ], 200);
    }

    /**
     * Create a new booking
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_id' => 'required|exists:services,id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'booking_date' => 'required|date|after:today',
            'booking_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();
            $customer = $user->customer;

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer profile not found',
                ], 404);
            }

            $booking = Booking::create([
                'customer_id' => $customer->id,
                'service_id' => $request->service_id,
                'vehicle_id' => $request->vehicle_id,
                'booking_date' => $request->booking_date,
                'booking_time' => $request->booking_time,
                'status' => 'pending',
                'notes' => $request->notes,
            ]);

            $booking->load(['service', 'vehicle']);

            return response()->json([
                'success' => true,
                'message' => 'Booking created successfully',
                'data' => $booking
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Booking creation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific booking
     *
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id, Request $request)
    {
        try {
            $user = $request->user();
            $booking = Booking::with(['customer', 'service', 'vehicle', 'assignedStaff'])->findOrFail($id);

            // Check authorization - customers can only see their own bookings
            if ($user->role && $user->role->name === 'customer') {
                if ($booking->customer_id !== ($user->customer->id ?? null)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized access',
                    ], 403);
                }
            }

            return response()->json([
                'success' => true,
                'data' => $booking
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found',
            ], 404);
        }
    }

    /**
     * Update a booking
     *
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_date' => 'sometimes|date',
            'booking_time' => 'sometimes|date_format:H:i',
            'status' => 'sometimes|in:pending,confirmed,in_progress,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();
            $booking = Booking::findOrFail($id);

            // Check authorization - customers can only update their own pending bookings
            if ($user->role && $user->role->name === 'customer') {
                if ($booking->customer_id !== ($user->customer->id ?? null) || $booking->status !== 'pending') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized to update this booking',
                    ], 403);
                }
            }

            $booking->update($request->only(['booking_date', 'booking_time', 'status', 'notes']));
            $booking->load(['service', 'vehicle']);

            return response()->json([
                'success' => true,
                'message' => 'Booking updated successfully',
                'data' => $booking
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Booking update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel a booking
     *
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id, Request $request)
    {
        try {
            $user = $request->user();
            $booking = Booking::findOrFail($id);

            // Check authorization
            if ($user->role && $user->role->name === 'customer') {
                if ($booking->customer_id !== ($user->customer->id ?? null)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized to cancel this booking',
                    ], 403);
                }
            }

            // Update status to cancelled instead of deleting
            $booking->update(['status' => 'cancelled']);

            return response()->json([
                'success' => true,
                'message' => 'Booking cancelled successfully',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Booking cancellation failed',
            ], 404);
        }
    }

    /**
     * Get current user's bookings (for customers)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function myBookings(Request $request)
    {
        $user = $request->user();
        $perPage = $request->input('per_page', 15);

        if (!$user->customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer profile not found',
            ], 404);
        }

        $bookings = Booking::where('customer_id', $user->customer->id)
            ->where('status', '!=', 'cancelled')
            ->with(['service', 'vehicle'])
            ->orderBy('booking_date', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $bookings
        ], 200);
    }

    /**
     * Get booking history (for customers)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function history(Request $request)
    {
        $user = $request->user();
        $perPage = $request->input('per_page', 15);

        if (!$user->customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer profile not found',
            ], 404);
        }

        $bookings = Booking::where('customer_id', $user->customer->id)
            ->whereIn('status', ['completed', 'cancelled'])
            ->with(['service', 'vehicle'])
            ->orderBy('booking_date', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $bookings
        ], 200);
    }

    /**
     * Get all bookings (for staff/admin)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function allBookings(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        $status = $request->input('status');

        $query = Booking::with(['customer', 'service', 'vehicle', 'assignedStaff']);

        if ($status) {
            $query->where('status', $status);
        }

        $bookings = $query->orderBy('booking_date', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $bookings
        ], 200);
    }
}
