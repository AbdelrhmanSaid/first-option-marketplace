<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Memo extends Model
{
    /** @use HasFactory<\Database\Factories\MemoFactory> */
    use HasFactory;

    /**
     * Attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'admin_id',
        'title',
        'icon',
        'date',
        'content',
        'attachments',
    ];

    /**
     * Attributes that should be cast to native types.
     *
     * @var array<int, string>
     */
    protected $casts = [
        'date' => 'datetime',
        'attachments' => 'array',
    ];

    /**
     * Memo belongs to Admin.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * Scope a query to only include memos for the authenticated admin.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForAuthenticatedAdmin($query)
    {
        $adminId = auth('admins')->id();

        return $query->where('admin_id', $adminId);
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('id', $value)->forAuthenticatedAdmin()->first() ?? abort(404);
    }
}
