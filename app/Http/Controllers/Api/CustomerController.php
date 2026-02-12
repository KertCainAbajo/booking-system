<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    /**
     * Get all customers (staff/admin only)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        $search = $request->input('search');

        $query = Customer::with('user');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $customers
        ], 200);
    }

    /**
     * Get a specific customer (staff/admin only)
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $customer = Customer::with(['user', 'bookings'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $customer
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found',
            ], 404);
        }
    }

    /**
     * Create a new customer (admin only)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $customerRole = \App\Models\Role::where('name', 'customer')->first();

            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role_id' => $customerRole->id,
            ]);

            $user->assignRole('customer');

            // Create customer record
            $customer = Customer::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Customer created successfully',
                'data' => $customer->load('user')
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Customer creation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a customer (admin only)
     *
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        try {
            $customer = Customer::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'phone' => 'sometimes|required|string|max:20',
                'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $customer->user_id,
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $customer->update($request->only(['name', 'phone', 'email']));

            if ($customer->user) {
                $customer->user->update($request->only(['name', 'phone', 'email']));
            }

            return response()->json([
                'success' => true,
                'message' => 'Customer updated successfully',
                'data' => $customer->load('user')
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Customer update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a customer (admin only)
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $user = $customer->user;

            $customer->delete();
            if ($user) {
                $user->delete();
            }

            return response()->json([
                'success' => true,
                'message' => 'Customer deleted successfully',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Customer deletion failed',
            ], 404);
        }
    }
}
