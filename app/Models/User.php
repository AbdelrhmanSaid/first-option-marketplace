<?php

namespace App\Models;

use App\Enums\SubscriptionStatus;
use Carbon\Carbon;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\URL;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasRoles, Notifiable, SoftDeletes;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_login_at' => 'datetime',
    ];

    /**
     * Perform any actions required after the model boots.
     */
    protected static function booted(): void
    {
        ResetPassword::createUrlUsing(function (User $user, string $token) {
            return url(route('website.password.reset', [
                'token' => $token,
                'email' => $user->email,
            ], false));
        });

        VerifyEmail::createUrlUsing(function (User $user) {
            return URL::temporarySignedRoute(
                'website.verification.verify',
                Carbon::now()->addMinutes(config('auth.verification.expire', 60)),
                [
                    'id' => $user->getKey(),
                    'hash' => sha1($user->getEmailForVerification()),
                ]
            );
        });
    }

    /**
     * Get the member account for the user.
     */
    public function member(): HasOne
    {
        return $this->hasOne(PublisherMember::class);
    }

    /**
     * Get the publisher account for the user.
     */
    public function publisher(): HasOneThrough
    {
        return $this->hasOneThrough(Publisher::class, PublisherMember::class, 'user_id', 'id', 'id', 'publisher_id');
    }

    /**
     * Get the subscriptions for the user.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Scope a query to only include users that have a publisher account.
     */
    public function scopePublisher(Builder $query): Builder
    {
        return $query->has('publisher');
    }

    /**
     * Check if the user has an active subscription for the addon.
     */
    public function hasActiveSubscription(Addon $addon): bool
    {
        return $this->subscriptions()->where('addon_id', $addon->id)->where('status', SubscriptionStatus::Active)->exists();
    }
}
