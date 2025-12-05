<?php

declare(strict_types=1);

use App\Filament\Admin\Pages\Settings\Preferences;
use App\Settings\PreferencesSettings;

use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

it('can render the page', function () {
    livewire(Preferences::class)
        ->assertSuccessful();
});

it('can open the route', function () {
    get(Preferences::getUrl())
        ->assertSuccessful();
});

it('can update preferences', function () {
    livewire(Preferences::class)
        ->fillForm([
            'timezone' => 'Europe/London',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect(app(PreferencesSettings::class))
        ->timezone->toBe('Europe/London');
});
