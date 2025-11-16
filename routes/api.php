<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| All routes are prefixed with /api
| Authentication via Laravel Sanctum
|
*/

// Public routes
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user()->load('branch');
    });

    Route::post('/logout', [AuthController::class, 'logout']);

    // Phase 1: Inventory Management Routes
    Route::prefix('inventory')->group(function () {
        // Material Types
        Route::apiResource('material-types', MaterialTypeController::class);

        // Brands
        Route::apiResource('brands', BrandController::class);

        // Inventory Items
        Route::apiResource('items', InventoryItemController::class);
        Route::get('items/{id}/price-history', [InventoryItemController::class, 'priceHistory']);
        Route::get('items/{id}/transactions', [InventoryItemController::class, 'transactions']);
        Route::get('items/low-stock', [InventoryItemController::class, 'lowStock']);

        // Purchase Orders
        Route::apiResource('purchase-orders', PurchaseOrderController::class);
        Route::post('purchase-orders/{id}/submit', [PurchaseOrderController::class, 'submit']);
        Route::post('purchase-orders/{id}/receive', [PurchaseOrderController::class, 'receive']);

        // Inventory Adjustments
        Route::apiResource('adjustments', InventoryAdjustmentController::class);
        Route::post('adjustments/{id}/approve', [InventoryAdjustmentController::class, 'approve']);
    });

    // Units reference data
    Route::get('/units', [UnitController::class, 'index']);

    // Branches
    Route::get('/branches', [BranchController::class, 'index']);
});
