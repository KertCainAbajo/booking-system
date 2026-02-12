<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Get all services (public endpoint)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 20);
        $categoryId = $request->input('category_id');

        $query = Service::with('category')->where('is_active', true);

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $services = $query->orderBy('name')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $services
        ], 200);
    }

    /**
     * Get a specific service (public endpoint)
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $service = Service::with(['category', 'inventoryItems'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $service
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Service not found',
            ], 404);
        }
    }
}
