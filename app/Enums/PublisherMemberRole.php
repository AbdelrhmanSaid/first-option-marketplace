<?php

namespace App\Enums;

use Illuminate\Support\Arr;

enum PublisherMemberRole: string
{
    case Owner = 'owner';
    case Member = 'member';
    case Admin = 'admin';

    /**
     * Get the label of the publisher member role.
     */
    public function label(): string
    {
        return match ($this) {
            self::Owner => __('Owner'),
            self::Member => __('Member'),
            self::Admin => __('Admin'),
        };
    }

    /**
     * Get the array of publisher member roles.
     */
    public static function toArray(): array
    {
        return Arr::mapWithKeys(self::cases(), fn (PublisherMemberRole $role) => [$role->value => $role->label()]);
    }
}
