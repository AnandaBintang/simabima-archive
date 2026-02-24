<?php

namespace App\Policies;

use App\Models\Archive;
use App\Models\User;

class ArchivePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Archive $archive): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Archive $archive): bool
    {
        return $user->isAdmin() || $archive->user_id === $user->id;
    }

    public function delete(User $user, Archive $archive): bool
    {
        return $user->isAdmin() || $archive->user_id === $user->id;
    }
}
