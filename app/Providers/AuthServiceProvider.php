<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\PurchaseOrder::class => \App\Policies\PurchaseOrderPolicy::class,
        \App\Models\ProductionOrder::class => \App\Policies\ProductionOrderPolicy::class,
        \App\Models\Sale::class => \App\Policies\SalePolicy::class,
        \App\Models\JournalEntry::class => \App\Policies\JournalEntryPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
