<?php

namespace App\Models;

use App\Enums\OS;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'screenshots',
        'youtube_video_url',
        'os',
        'privacy_policy_url',
        'terms_of_service_url',
        'learn_more_url',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'screenshots' => 'array',
        'os' => OS::class,
    ];

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
