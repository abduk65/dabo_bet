<?php
use App\Http\Controllers\DailyProductionAdjustmentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BranchController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CashCollectedController;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\CommissionRecipientController;
use App\Http\Controllers\DailyInventoryOutController;
use App\Http\Controllers\DailySalesController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InventoryAdjustmentController;
use App\Http\Controllers\InventoryItemController;
use App\Http\Controllers\ProductTypeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\StandardBatchVarietyController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\WorkOrderController;

Auth::routes();


Route::get('/', function () {
    // $user = Auth::user();
    // $userPermission = $user->hasPermission('update', InventoryItem::class);
    // echo $userPermission;
    return view('welcome');
})->name('front');

Route::get(
    '/user_role',
    function () {
        echo auth()->user()->role;
    }
);

Route::get(
    '/logout',
    function () {
        Auth::logout();
        return redirect(route('front'));
    }
);

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [InventoryItemController::class, 'index'])->name('dashboard');
    // Route::get('/inventory', [InventoryItemController::class, 'index'])->name('inventory.index');
    // Route::get('/inventory/create', [InventoryItemController::class, 'create'])->name('inventory.create');
    // Route::post('/inventory', [InventoryItemController::class, 'store'])->name('inventory.store');
    // Route::get('/inventory/{post}', [InventoryItemController::class, 'show'])->name('inventory.show');
    // Route::get('/inventory/{post}/edit', [InventoryItemController::class, 'edit'])->name('inventory.edit');
    // Route::put('/inventory/{post}', [InventoryItemController::class, 'update'])->name('inventory.update');
    // Route::delete('/inventory/{post}', [InventoryItemController::class, 'destroy'])->name('inventory.delete');

    // Route::get('/dailyInventoryOut', [DailyInventoryOutController::class, 'index'])->name('dailyInventoryOut.index');
    // Route::get('/dailyInventoryOut/create', [DailyInventoryOutController::class, 'create'])->name('dailyInventoryOut.create');
    // Route::post('/dailyInventoryOut', [DailyInventoryOutController::class, 'store'])->name('dailyInventoryOut.store');
    // Route::get('/dailyInventoryOut/{post}', [DailyInventoryOutController::class, 'show'])->name('dailyInventoryOut.show');
    // Route::get('/dailyInventoryOut/{post}/edit', [DailyInventoryOutController::class, 'edit'])->name('dailyInventoryOut.edit');
    // Route::put('/dailyInventoryOut/{post}', [DailyInventoryOutController::class, 'update'])->name('dailyInventoryOut.update');
    // Route::delete('/dailyInventoryOut/{post}', [DailyInventoryOutController::class, 'destroy'])->name('dailyInventoryOut.delete');

    Route::resource('inventory', InventoryItemController::class);
    Route::resource('dailyInventoryOut', DailyInventoryOutController::class);
    Route::resource('brand', BrandController::class);
    Route::resource('unit', UnitController::class);
    Route::resource('productType', ProductTypeController::class);
    Route::resource('product', ProductController::class);
    Route::resource('recipe', RecipeController::class);
    Route::resource('workOrder', WorkOrderController::class);
    Route::resource('standardBatchVariety', StandardBatchVarietyController::class);

    Route::resource('branch', BranchController::class);
    Route::resource('dailyProductionAdjustment', DailyProductionAdjustmentController::class);
    Route::resource('inventoryAdjustment', InventoryAdjustmentController::class);

    Route::resource('expense', ExpenseController::class);
    Route::resource('cashCollected', CashCollectedController::class);
    Route::resource('dailySales', DailySalesController::class);
    Route::resource('commissionRecipient', CommissionRecipientController::class);

    Route::resource('commission', CommissionController::class);

});


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
