<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductionOrder;
use App\Models\ProductionMaterialConsumption;
use App\Models\ProductionOutput;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ProductionOrderController extends Controller
{
    /**
     * Display a listing of production orders
     */
    public function index(Request $request): JsonResponse
    {
        $query = ProductionOrder::with(['recipe.product', 'supervisor']);

        // Filter by status
        if ($request->has('status')) {
            $query->status($request->status);
        }

        // Filter active only
        if ($request->boolean('active_only')) {
            $query->active();
        }

        // Filter by date range
        if ($request->has('from_date')) {
            $query->whereDate('planned_start_time', '>=', $request->from_date);
        }

        $orders = $query->orderBy('planned_start_time', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }

    /**
     * Store a newly created production order
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'recipe_id' => 'required|exists:recipes,id',
            'scaling_factor' => 'required|numeric|min:0.1',
            'planned_start_time' => 'required|date',
            'supervisor_user_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $order = ProductionOrder::create([
                ...$validated,
                'status' => 'planned',
            ]);

            // Create planned material consumption from recipe
            $order->createPlannedMaterialConsumption(auth()->id());

            $order->load([
                'recipe.product',
                'supervisor',
                'materialConsumption.inventoryItem.materialType'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Production order created successfully',
                'data' => $order,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create production order: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified production order
     */
    public function show(ProductionOrder $productionOrder): JsonResponse
    {
        $productionOrder->load([
            'recipe.product',
            'recipe.components.inventoryItem',
            'supervisor',
            'materialConsumption.inventoryItem.materialType',
            'materialConsumption.inventoryItem.brand',
            'output.product',
            'substitutions'
        ]);

        return response()->json([
            'success' => true,
            'data' => $productionOrder,
        ]);
    }

    /**
     * Start production
     */
    public function start(ProductionOrder $productionOrder): JsonResponse
    {
        if ($productionOrder->status !== 'planned') {
            return response()->json([
                'success' => false,
                'message' => 'Only planned production orders can be started',
            ], 422);
        }

        $productionOrder->start();

        return response()->json([
            'success' => true,
            'message' => 'Production started successfully',
            'data' => $productionOrder,
        ]);
    }

    /**
     * Record material consumption
     */
    public function recordConsumption(Request $request, ProductionOrder $productionOrder): JsonResponse
    {
        if ($productionOrder->status !== 'in_progress') {
            return response()->json([
                'success' => false,
                'message' => 'Production order must be in progress to record consumption',
            ], 422);
        }

        $validated = $request->validate([
            'consumptions' => 'required|array|min:1',
            'consumptions.*.id' => 'required|exists:production_material_consumption,id',
            'consumptions.*.actual_quantity' => 'required|numeric|min:0',
            'consumptions.*.variance_reason' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            foreach ($validated['consumptions'] as $consumptionData) {
                $consumption = ProductionMaterialConsumption::find($consumptionData['id']);
                $consumption->recordActualUsage(
                    $consumptionData['actual_quantity'],
                    $consumptionData['variance_reason'] ?? null
                );
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Material consumption recorded successfully',
                'data' => $productionOrder->fresh(['materialConsumption.inventoryItem']),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to record consumption: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Record production output
     */
    public function recordOutput(Request $request, ProductionOrder $productionOrder): JsonResponse
    {
        if ($productionOrder->status !== 'in_progress') {
            return response()->json([
                'success' => false,
                'message' => 'Production order must be in progress to record output',
            ], 422);
        }

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity_produced' => 'required|numeric|min:0.01',
            'quantity_good' => 'required|numeric|min:0',
            'quantity_rejected' => 'required|numeric|min:0',
            'rejection_reason' => 'nullable|string',
        ]);

        $validated['production_order_id'] = $productionOrder->id;
        $validated['recorded_by_user_id'] = auth()->id();

        $output = ProductionOutput::create($validated);
        $output->calculateUnitProductionCost();

        return response()->json([
            'success' => true,
            'message' => 'Production output recorded successfully',
            'data' => $output,
        ], 201);
    }

    /**
     * Complete production order
     */
    public function complete(ProductionOrder $productionOrder): JsonResponse
    {
        if ($productionOrder->status !== 'in_progress') {
            return response()->json([
                'success' => false,
                'message' => 'Only in-progress production orders can be completed',
            ], 422);
        }

        $productionOrder->complete();

        return response()->json([
            'success' => true,
            'message' => 'Production order completed successfully',
            'data' => $productionOrder->fresh(['output', 'materialConsumption']),
        ]);
    }

    /**
     * Get material consumption for order
     */
    public function consumption(ProductionOrder $productionOrder): JsonResponse
    {
        $consumption = $productionOrder->materialConsumption()
            ->with(['inventoryItem.materialType', 'inventoryItem.brand', 'recordedBy'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $consumption,
        ]);
    }

    /**
     * Get production output for order
     */
    public function output(ProductionOrder $productionOrder): JsonResponse
    {
        $output = $productionOrder->output()
            ->with(['product', 'recordedBy'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $output,
        ]);
    }
}
