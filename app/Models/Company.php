<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'name',
        'support_email',
        'links',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'social_media' => 'array',
    ];

    /**
     * Get the user that owns the company.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
