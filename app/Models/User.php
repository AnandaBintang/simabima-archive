<?php

namespace App\Models;

use App\Enums\UserRole;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'organization_unit_id',
        'jabatan',
        'unit_kerja',
        'phone',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Administrator;
    }

    public function isStaff(): bool
    {
        return $this->role === UserRole::Staff;
    }

    public function organizationUnit(): BelongsTo
    {
        return $this->belongsTo(OrganizationUnit::class);
    }

    /**
     * Get all organization unit IDs that this user should have access to.
     * Staff in UPT: only their own UPT branch.
     * Staff in Sekretariat/Bidang: no restriction (sees everything).
     * Admin: null (no restriction).
     */
    public function getAccessibleUnitIds(): ?array
    {
        if ($this->isAdmin()) {
            return null;
        }

        if (! $this->organization_unit_id) {
            return [];
        }

        // Walk up to find the top-level group
        $unit = $this->organizationUnit;
        if (! $unit) {
            return [];
        }

        $group = $unit;
        while ($group->parent_id) {
            $group = $group->parent;
        }

        // If staff is NOT under "UPT" group, they can see everything
        if ($group->type !== 'group' || strtolower($group->name) !== 'upt') {
            return null;
        }

        // UPT staff: only their own UPT branch
        $uptBranch = $unit;
        while ($uptBranch->parent_id && $uptBranch->parent_id !== $group->id) {
            $uptBranch = $uptBranch->parent;
        }
        $uptBranch->load('children.children');

        return $this->collectDescendantIds($uptBranch);
    }

    private function collectDescendantIds(OrganizationUnit $unit): array
    {
        $ids = [$unit->id];
        foreach ($unit->children as $child) {
            $ids = array_merge($ids, $this->collectDescendantIds($child));
        }
        return $ids;
    }

    public function archives(): HasMany
    {
        return $this->hasMany(Archive::class);
    }
}
