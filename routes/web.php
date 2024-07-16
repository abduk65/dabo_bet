<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\DailyInventoryOutController;
use App\Http\Controllers\InventoryItemController;
use App\Http\Controllers\ProductTypeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\UnitController;
use App\Models\InventoryItem;
use App\Models\ProductType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

;

Route::get('/', function () {
    // $user = Auth::user();
    // $userPermission = $user->hasPermission('update', InventoryItem::class);
    // echo $userPermission;
    return view('welcome');
});

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
        return redirect(route('/'));
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
});
