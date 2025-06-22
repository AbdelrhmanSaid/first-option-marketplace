<?php

namespace App\Models;

use App\Enums\OS;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Addon extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'publisher_id',
        'software_id',
        'slug',
        'name',
        'short_description',
        'description',
        'instructions',
        'icon',
        'price',
        'trial_period',
        'screenshots',
        'youtube_video_url',
        'os',
        'privacy_policy_url',
        'terms_of_service_url',
        'learn_more_url',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'screenshots' => 'array',
        'os' => OS::class,
        'is_active' => 'boolean',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($addon) {
            if (empty($addon->slug)) {
                $addon->slug = static::generateUniqueSlug($addon->name);
            }
        });
    }

    /**
     * Generate a unique slug for the addon.
     */
    protected static function generateUniqueSlug(string $name): string
    {
        $slug = sprintf('addon-%s-%s', Str::slug($name), Str::random(5));

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
     * Get the publisher that owns the addon.
     */
    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
    }

    /**
     * Get the software that owns the addon.
     */
    public function software(): BelongsTo
    {
        return $this->belongsTo(Software::class);
    }

    /**
     * Get the versions of the addon.
     */
    public function versions(): HasMany
    {
        return $this->hasMany(AddonVersion::class);
    }
}
