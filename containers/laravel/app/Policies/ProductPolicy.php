<?php

namespace App\Policies;

use App\Models\AuthenticateAccount;
use App\Models\Product;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(AuthenticateAccount $authenticateAccount): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(AuthenticateAccount $authenticateAccount, Product $product): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(AuthenticateAccount $authenticateAccount): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(AuthenticateAccount $authenticateAccount, Product $product): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(AuthenticateAccount $authenticateAccount, Product $product): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(AuthenticateAccount $authenticateAccount, Product $product): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(AuthenticateAccount $authenticateAccount, Product $product): bool
    {
        return false;
    }
}
