<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrganizationUnit extends Model
{
    protected $fillable = [
        'name',
        'type',
        'parent_id',
        'order',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(OrganizationUnit::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(OrganizationUnit::class, 'parent_id')->orderBy('order');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function archives(): HasMany
    {
        return $this->hasMany(Archive::class);
    }

    /**
     * Top-level groups shown in sidebar dropdown (Sekretariat, Bidang, UPT).
     */
    public function scopeGroups(Builder $query): Builder
    {
        return $query->where('type', 'group')->orderBy('order');
    }

    /**
     * Get full hierarchy path (e.g. "Sekretariat > Unit Gudang")
     */
    public function getFullPathAttribute(): string
    {
        $path = [$this->name];
        $parent = $this->parent;

        while ($parent) {
            array_unshift($path, $parent->name);
            $parent = $parent->parent;
        }

        return implode(' > ', $path);
    }
}
