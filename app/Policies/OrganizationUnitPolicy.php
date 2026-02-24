<?php

namespace App\Policies;

use App\Models\OrganizationUnit;
use App\Models\User;

class OrganizationUnitPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, OrganizationUnit $organizationUnit): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, OrganizationUnit $organizationUnit): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, OrganizationUnit $organizationUnit): bool
    {
        return $user->isAdmin();
    }
}
