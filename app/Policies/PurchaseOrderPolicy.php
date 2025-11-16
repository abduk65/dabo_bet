<?php

namespace App\Policies;

use App\Models\PurchaseOrder;
use App\Models\User;

class PurchaseOrderPolicy
{
    /**
     * Determine if the user can view any purchase orders.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view purchase orders
        return true;
    }

    /**
     * Determine if the user can view the purchase order.
     */
    public function view(User $user, PurchaseOrder $purchaseOrder): bool
    {
        // All authenticated users can view specific purchase orders
        return true;
    }

    /**
     * Determine if the user can create purchase orders.
     */
    public function create(User $user): bool
    {
        // Managers and above can create purchase orders
        return $user->isManager();
    }

    /**
     * Determine if the user can update the purchase order.
     */
    public function update(User $user, PurchaseOrder $purchaseOrder): bool
    {
        // Only draft orders can be updated
        // Only by managers and above
        return $purchaseOrder->status === 'draft' && $user->isManager();
    }

    /**
     * Determine if the user can submit the purchase order for approval.
     */
    public function submit(User $user, PurchaseOrder $purchaseOrder): bool
    {
        // Managers and above can submit
        // Only draft orders
        return $purchaseOrder->status === 'draft' && $user->isManager();
    }

    /**
     * Determine if the user can approve the purchase order.
     */
    public function approve(User $user, PurchaseOrder $purchaseOrder): bool
    {
        // Only owners can approve
        // Only submitted orders
        return $purchaseOrder->status === 'submitted' && $user->isOwner();
    }

    /**
     * Determine if the user can receive the purchase order.
     */
    public function receive(User $user, PurchaseOrder $purchaseOrder): bool
    {
        // Supervisors and above can receive
        // Only approved orders
        return $purchaseOrder->status === 'approved' && $user->isSupervisor();
    }

    /**
     * Determine if the user can cancel the purchase order.
     */
    public function cancel(User $user, PurchaseOrder $purchaseOrder): bool
    {
        // Managers and above can cancel
        // Only draft or submitted orders
        return in_array($purchaseOrder->status, ['draft', 'submitted']) && $user->isManager();
    }

    /**
     * Determine if the user can delete the purchase order.
     */
    public function delete(User $user, PurchaseOrder $purchaseOrder): bool
    {
        // Only owners can delete
        // Only draft orders
        return $purchaseOrder->status === 'draft' && $user->isOwner();
    }
}
