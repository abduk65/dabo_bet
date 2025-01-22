<?php

use App\Models\Brand;
use App\Models\DailyInventoryOut;
use App\Models\InventoryItem;
use App\Models\ProductType;
use App\Models\Unit;
use App\Models\Product;

return [
    'worker' => [
        'read' => [
            InventoryItem::class,
            DailyInventoryOut::class,
            Unit::class,
            Brand::class,
            ProductType::class,
            Product::class
        ],
        'store' => [
            InventoryItem::class,
            DailyInventoryOut::class,
            Unit::class,
            Brand::class,
            ProductType::class,
            Product::class
        ],
        'update' => [
            InventoryItem::class,
            DailyInventoryOut::class,
            Unit::class,
            Brand::class,
            ProductType::class,
            Product::class
        ],
        'delete' => [
            InventoryItem::class,
            DailyInventoryOut::class,
            Unit::class,
            Brand::class,
            ProductType::class,
            Product::class
        ],
    ],

    'store_keeper' => [
        'read' => [
            InventoryItem::class,
            DailyInventoryOut::class,
            Unit::class,
            Brand::class,
            ProductType::class,
            Product::class
        ],
        'store' => [
            InventoryItem::class,
            DailyInventoryOut::class,
            Unit::class,
            Brand::class,
            ProductType::class,
            Product::class
        ],
        'update' => [
            InventoryItem::class,
            DailyInventoryOut::class,
            Unit::class,
            Brand::class,
            ProductType::class,
            Product::class
        ],
        'delete' => [
            InventoryItem::class,
            DailyInventoryOut::class,
            Unit::class,
            Brand::class,
            ProductType::class,
            Product::class
        ],
    ],

];
