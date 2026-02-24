<?php

namespace App\Policies;

use App\Models\ArchiveCategory;
use App\Models\User;

class ArchiveCategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, ArchiveCategory $archiveCategory): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, ArchiveCategory $archiveCategory): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, ArchiveCategory $archiveCategory): bool
    {
        return $user->isAdmin();
    }
}
