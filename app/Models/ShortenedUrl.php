<?php

namespace App\Models;

use App\Traits\Taggable;
use App\Traits\UserAuditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShortenedUrl extends Model
{
    /** @use HasFactory<\Database\Factories\ShortenedUrlFactory> */
    use HasFactory;

    use SoftDeletes, Taggable, UserAuditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'url',
        'slug',
        'title',
        'clicks',
        'tags',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tags' => 'array',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
