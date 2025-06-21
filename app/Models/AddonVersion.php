<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AddonVersion extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'addon_id',
        'version',
        'resource',
    ];

    /**
     * Get the addon that owns the version.
     */
    public function addon(): BelongsTo
    {
        return $this->belongsTo(Addon::class);
    }
}
