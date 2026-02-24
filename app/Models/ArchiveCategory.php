<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ArchiveCategory extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function archives(): HasMany
    {
        return $this->hasMany(Archive::class);
    }
}
