<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        if (!in_array($user->role?->name, ['customer', 'business_owner', 'super_admin'], true)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        $perPage = min(100, max(1, (int) $request->input('per_page', 15)));

        // Customers see only their bookings
        if ($user->role && $user->role->name === 'customer') {
            $bookings = Booking::where('customer_id', $user->customer->id ?? null)
                ->with(['services', 'vehicle'])
                ->orderBy('booking_date', 'desc')
                ->paginate($perPage);
        } else {
            // Owners and admins see all bookings
            $bookings = Booking::with(['customer', 'services', 'vehicle'])
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
        // Accept a list of services; a single service_id is still supported for older clients
        if (! $request->has('service_ids') && $request->filled('service_id')) {
            $request->merge(['service_ids' => [$request->input('service_id')]]);
        }

        $validator = Validator::make($request->all(), [
            'service_ids' => 'required|array|min:1',
            'service_ids.*' => 'integer|distinct|exists:services,id,is_active,1',
            'vehicle_id' => 'required|exists:vehicles,id',
            'booking_date' => 'required|date|after:today',
            'booking_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:1000',
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

            if (!$customer || $user->role?->name !== 'customer') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only customer accounts can create bookings.',
                ], 403);
            }

            if (!$customer->vehicles()->whereKey($request->vehicle_id)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'The selected vehicle does not belong to this customer.',
                ], 403);
            }

            // Services are linked through booking_services and priced from the database
            $services = Service::whereIn('id', $request->input('service_ids'))->where('is_active', true)->get();

            $booking = DB::transaction(function () use ($request, $customer, $services) {
                $booking = Booking::create([
                    'customer_id' => $customer->id,
                    'vehicle_id' => $request->vehicle_id,
                    'booking_date' => $request->booking_date,
                    'booking_time' => $request->booking_time,
                    'status' => 'pending',
                    'total_amount' => $services->sum('base_price'),
                    'notes' => $request->notes,
                ]);

                foreach ($services as $service) {
                    $booking->services()->attach($service->id, ['quantity' => 1, 'price' => $service->base_price]);
                }

                return $booking;
            });

            $booking->load(['services', 'vehicle']);

            return response()->json([
                'success' => true,
                'message' => 'Booking created successfully',
                'data' => $booking
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Booking creation failed',
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
            if (!in_array($user->role?->name, ['customer', 'business_owner', 'super_admin'], true)) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            $booking = Booking::with(['customer', 'services', 'vehicle'])->findOrFail($id);

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
            'status' => 'sometimes|in:pending,approved,completed,cancelled,no_show,not_available',
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
            if (!in_array($user->role?->name, ['customer', 'business_owner', 'super_admin'], true)) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
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

            $booking->update($request->only(['booking_date', 'booking_time', 'notes']));

            // Only owners and super admins may change booking status; the change is logged like on the website
            if ($request->filled('status') && in_array($user->role?->name, ['business_owner', 'super_admin'], true)
                && $request->input('status') !== $booking->status) {
                $booking->updateStatus($request->input('status'), $user->id, 'Changed through API');
            }
            $booking->load(['services', 'vehicle']);

            return response()->json([
                'success' => true,
                'message' => 'Booking updated successfully',
                'data' => $booking
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Booking update failed',
                'error' => 'An unexpected error occurred.'
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

            // Check authorization and enforce the same cancellation rules as the web application.
            if ($user->role && $user->role->name === 'customer') {
                if ($booking->customer_id !== ($user->customer->id ?? null)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized to cancel this booking',
                    ], 403);
                }
            } elseif (!in_array($user->role?->name, ['business_owner', 'super_admin'], true)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to cancel this booking',
                ], 403);
            }

            if (!$booking->canBeCancelled()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This booking cannot be cancelled.',
                ], 422);
            }

            $booking->cancel($user->id, 'Cancelled through API');

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
        $perPage = min(100, max(1, (int) $request->input('per_page', 15)));

        if (!$user->customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer profile not found',
            ], 404);
        }

        $bookings = Booking::where('customer_id', $user->customer->id)
            ->where('status', '!=', 'cancelled')
            ->with(['services', 'vehicle'])
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
        $perPage = min(100, max(1, (int) $request->input('per_page', 15)));

        if (!$user->customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer profile not found',
            ], 404);
        }

        $bookings = Booking::where('customer_id', $user->customer->id)
            ->whereIn('status', ['completed', 'cancelled'])
            ->with(['services', 'vehicle'])
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
        if (!in_array($request->user()->role?->name, ['business_owner', 'super_admin'], true)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $perPage = min(100, max(1, (int) $request->input('per_page', 15)));
        $status = $request->input('status');

        $query = Booking::with(['customer', 'services', 'vehicle']);

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
