<?php

declare(strict_types=1);

use App\Filament\Admin\Pages\Settings\Preferences;
use App\Settings\PreferencesSettings;

use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

it('can render the page', function (): void {
    livewire(Preferences::class)
        ->assertSuccessful();
});

it('can open the route', function (): void {
    get(Preferences::getUrl())
        ->assertSuccessful();
});

it('can update preferences', function (): void {
    livewire(Preferences::class)
        ->fillForm([
            'timezone' => 'Europe/London',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect(resolve(PreferencesSettings::class))
        ->timezone->toBe('Europe/London');
});
