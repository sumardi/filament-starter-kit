<?php

declare(strict_types=1);

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

final class PreferencesSettings extends Settings
{
    public string $locale;

    public string $timezone;

    public string $dateFormat;

    public string $timeFormat;

    public static function group(): string
    {
        return 'preferences';
    }
}
