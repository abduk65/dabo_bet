<?php

namespace App\Policies;

use App\Models\ProductionOrder;
use App\Models\User;

class ProductionOrderPolicy
{
    /**
     * Determine if the user can view any production orders.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view production orders
        return true;
    }

    /**
     * Determine if the user can view the production order.
     */
    public function view(User $user, ProductionOrder $productionOrder): bool
    {
        // All authenticated users can view specific production orders
        return true;
    }

    /**
     * Determine if the user can create production orders.
     */
    public function create(User $user): bool
    {
        // Managers and above can create production orders
        return $user->isManager();
    }

    /**
     * Determine if the user can update the production order.
     */
    public function update(User $user, ProductionOrder $productionOrder): bool
    {
        // Only planned orders can be updated
        // Only by managers and above
        return $productionOrder->status === 'planned' && $user->isManager();
    }

    /**
     * Determine if the user can start the production order.
     */
    public function start(User $user, ProductionOrder $productionOrder): bool
    {
        // Supervisors and above can start production
        // Only planned orders
        return $productionOrder->status === 'planned' && $user->isSupervisor();
    }

    /**
     * Determine if the user can record consumption.
     */
    public function recordConsumption(User $user, ProductionOrder $productionOrder): bool
    {
        // Supervisors and above can record consumption
        // Only in-progress orders
        return $productionOrder->status === 'in_progress' && $user->isSupervisor();
    }

    /**
     * Determine if the user can record output.
     */
    public function recordOutput(User $user, ProductionOrder $productionOrder): bool
    {
        // Supervisors and above can record output
        // Only in-progress orders
        return $productionOrder->status === 'in_progress' && $user->isSupervisor();
    }

    /**
     * Determine if the user can complete the production order.
     */
    public function complete(User $user, ProductionOrder $productionOrder): bool
    {
        // Managers and above can complete
        // Only in-progress orders
        return $productionOrder->status === 'in_progress' && $user->isManager();
    }

    /**
     * Determine if the user can delete the production order.
     */
    public function delete(User $user, ProductionOrder $productionOrder): bool
    {
        // Only owners can delete
        // Only planned orders
        return $productionOrder->status === 'planned' && $user->isOwner();
    }
}
