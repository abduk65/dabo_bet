<?php

namespace App\Policies;

use App\Models\Sale;
use App\Models\User;

class SalePolicy
{
    /**
     * Determine if the user can view any sales.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view sales
        return true;
    }

    /**
     * Determine if the user can view the sale.
     */
    public function view(User $user, Sale $sale): bool
    {
        // All users can view sales
        // Employees can only view sales from their branch
        if ($user->role === 'employee') {
            return $sale->branch_id === $user->branch_id;
        }

        return true;
    }

    /**
     * Determine if the user can create sales.
     */
    public function create(User $user): bool
    {
        // All users can create sales
        return true;
    }

    /**
     * Determine if the user can update the sale.
     */
    public function update(User $user, Sale $sale): bool
    {
        // Only draft sales can be updated
        // Employees can only update their own branch's sales
        if ($sale->status !== 'draft') {
            return false;
        }

        if ($user->role === 'employee') {
            return $sale->branch_id === $user->branch_id;
        }

        return $user->isSupervisor();
    }

    /**
     * Determine if the user can complete the sale.
     */
    public function complete(User $user, Sale $sale): bool
    {
        // Supervisors and above can complete
        // Only draft sales
        if ($sale->status !== 'draft') {
            return false;
        }

        if ($user->role === 'supervisor') {
            return $sale->branch_id === $user->branch_id;
        }

        return $user->isManager();
    }

    /**
     * Determine if the user can cancel the sale.
     */
    public function cancel(User $user, Sale $sale): bool
    {
        // Managers and above can cancel
        // Only draft sales
        return $sale->status === 'draft' && $user->isManager();
    }

    /**
     * Determine if the user can delete the sale.
     */
    public function delete(User $user, Sale $sale): bool
    {
        // Only owners can delete
        // Only draft sales
        return $sale->status === 'draft' && $user->isOwner();
    }

    /**
     * Determine if the user can view sales reports.
     */
    public function viewReports(User $user): bool
    {
        // Only managers and above can view comprehensive sales reports
        return $user->isManager();
    }
}
