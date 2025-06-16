<?php

namespace App\Models;

use App\Enums\PublisherMemberRole;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Publisher extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'headline',
        'email',
        'logo',
        'website',
        'is_verified',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_verified' => 'boolean',
    ];

    /**
     * Get the publisher members.
     */
    public function members(): HasMany
    {
        return $this->hasMany(PublisherMember::class);
    }

    /**
     * Get the publisher owner.
     */
    public function owner(): BelongsTo
    {
        return $this->members()->where('role', PublisherMemberRole::Owner->value)->first();
    }

    /**
     * Get the publisher admins.
     */
    public function admins(): HasMany
    {
        return $this->members()->where('role', PublisherMemberRole::Admin->value)->get();
    }
}
