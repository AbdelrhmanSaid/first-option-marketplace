<?php

namespace App\Models;

use App\Enums\PublisherMemberRole;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

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
        'slug',
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
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($publisher) {
            if (empty($publisher->slug)) {
                $publisher->slug = static::generateUniqueSlug($publisher->name);
            }
        });
    }

    /**
     * Generate a unique slug for the publisher.
     */
    protected static function generateUniqueSlug(string $name): string
    {
        $slug = sprintf('pub-%s-%s', Str::slug($name), Str::random(5));

        if (static::where('slug', $slug)->exists()) {
            return static::generateUniqueSlug($name);
        }

        return $slug;
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

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

    /**
     * Get the addons for the publisher.
     */
    public function addons(): HasMany
    {
        return $this->hasMany(Addon::class);
    }
}
