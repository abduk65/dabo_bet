<?php

use App\Models\Brand;
use App\Models\DailyInventoryOut;
use App\Models\InventoryItem;
use App\Models\ProductType;
use App\Models\Unit;

return [

    'worker' => [
        'read' => [
            InventoryItem::class,
            DailyInventoryOut::class,
            Unit::class,
            Brand::class,
            ProductType::class
        ],
        'store' => [
            InventoryItem::class,
            DailyInventoryOut::class,
            Unit::class,
            Brand::class,
            ProductType::class
        ],
        'update' => [
            InventoryItem::class,
            DailyInventoryOut::class,
            Unit::class,
            Brand::class,
            ProductType::class
        ],
        'delete' => [
            InventoryItem::class,
            DailyInventoryOut::class,
            Unit::class,
            Brand::class,
            ProductType::class
        ],
    ],

    'store_keeper' => [
        'read' => [
            InventoryItem::class,
            DailyInventoryOut::class,
            Unit::class,
            Brand::class,
            ProductType::class
        ],
        'store' => [
            InventoryItem::class,
            DailyInventoryOut::class,
            Unit::class,
            Brand::class,
            ProductType::class
        ],
        'update' => [
            InventoryItem::class,
            DailyInventoryOut::class,
            Unit::class,
            Brand::class,
            ProductType::class
        ],
        'delete' => [
            InventoryItem::class,
            DailyInventoryOut::class,
            Unit::class,
            Brand::class,
            ProductType::class
        ],
    ],

];
