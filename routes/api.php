<?php
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CashCollectedController;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\CommissionRecipientController;
use App\Http\Controllers\DailyInventoryOutController;
use App\Http\Controllers\DailySalesController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InventoryItemController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductTypeController;
use App\Http\Controllers\StandardBatchVarietyController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\WorkOrderController;
use Illuminate\Http\Request;
use App\Http\Controllers\BranchController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\DailyProductionAdjustmentController;
use App\Http\Controllers\InventoryAdjustmentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', [LoginController::class, 'loginApi']);
Route::post('logout', [LoginController::class, 'logoutApi']);
Route::get('users', [LoginController::class, 'getAllUsers']);

Route::apiResource('branches', BranchController::class);
Route::apiResource('brands', BrandController::class);
Route::apiResource('dailySales', DailySalesController::class);
Route::apiResource('products', ProductController::class);
Route::apiResource('commissions', CommissionController::class);
Route::apiResource('commissionRecipients', CommissionRecipientController::class);
Route::apiResource('productType', ProductTypeController::class);
Route::apiResource('units', UnitController::class);
Route::apiResource('standardBatchVariety', StandardBatchVarietyController::class);
Route::apiResource('inventoryItem', InventoryItemController::class);
Route::apiResource('cashCollected', CashCollectedController::class);
Route::apiResource('workOrder', WorkOrderController::class);
Route::apiResource('dailyInventoryOut', DailyInventoryOutController::class);
Route::apiResource('recipe', RecipeController::class);
Route::apiResource('expenses', ExpenseController::class);
Route::apiResource('dailyProductionAdjustment', DailyProductionAdjustmentController::class);
Route::apiResource('inventoryAdjustment', InventoryAdjustmentController::class);

Route::post('createDailySales', [DailySalesController::class, 'createDailySales']);
Route::get('profitReport', [DailySalesController::class, 'profitReport']);

Route::get('getGh', [DailySalesController::class, 'getGh']);

Route::get('roles', function () {
    return config('roles');
});
