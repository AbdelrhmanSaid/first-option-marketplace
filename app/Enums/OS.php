<?php

namespace App\Enums;

use Illuminate\Support\Arr;

enum OS: string
{
    case Win32 = 'win32';
    case Win64 = 'win64';
    case MacOS = 'macos';
    case Linux = 'linux';

    /**
     * Get the label of the OS.
     */
    public function label(): string
    {
        return match ($this) {
            self::Win32 => __('Windows 32-bit'),
            self::Win64 => __('Windows 64-bit'),
            self::MacOS => __('macOS'),
            self::Linux => __('Linux'),
        };
    }

    /**
     * Get the array of OS.
     */
    public static function toArray(): array
    {
        return Arr::mapWithKeys(self::cases(), fn (OS $os) => [$os->value => $os->label()]);
    }
}
