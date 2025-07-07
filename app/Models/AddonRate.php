<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddonRate extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'addon_id',
        'user_id',
        'rate',
        'comment',
        'reply',
        'is_approved',
    ];

    /**
     * Get the addon that the rate belongs to.
     */
    public function addon()
    {
        return $this->belongsTo(Addon::class);
    }

    /**
     * Get the user that the rate belongs to.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
