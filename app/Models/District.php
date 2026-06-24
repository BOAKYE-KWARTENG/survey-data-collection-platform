<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class District extends Model
{
    protected $fillable = ['region_id', 'name', 'code'];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function communities(): HasMany
    {
        return $this->hasMany(Community::class);
    }

    public function households(): HasMany
    {
        return $this->hasMany(Household::class);
    }
    
}
