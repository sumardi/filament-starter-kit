<?php

declare(strict_types=1);

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('preferences.locale', 'en');
        $this->migrator->add('preferences.timezone', 'UTC');
        $this->migrator->add('preferences.dateFormat', 'Y-m-d');
        $this->migrator->add('preferences.timeFormat', 'H:i:s');
    }
};
