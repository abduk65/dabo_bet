<?php

namespace App\Policies;

use App\Models\ProductType;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductTypePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('read', ProductType::class);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ProductType $productType): bool
    {
        return $user->hasPermission('read', ProductType::class);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('store', ProductType::class);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ProductType $productType): bool
    {
        return $user->hasPermission('update', ProductType::class);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProductType $productType): bool
    {
        return $user->hasPermission('delete', ProductType::class);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ProductType $productType): bool
    {
        return $user->hasPermission('update', ProductType::class);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ProductType $productType): bool
    {
        return $user->hasPermission('delete', ProductType::class);
    }
}
