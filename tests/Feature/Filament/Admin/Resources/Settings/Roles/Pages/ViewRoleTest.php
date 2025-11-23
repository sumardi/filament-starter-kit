<?php

declare(strict_types=1);

use App\Filament\Admin\Resources\Settings\Roles\Pages\ViewRole;
use App\Models\Role;

use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $this->role = Role::factory()->create();
});

it('can render role view page', function (): void {
    get(ViewRole::getUrl(['record' => $this->role]))
        ->assertSuccessful();
});

it('displays correct role details', function (): void {
    livewire(ViewRole::class, ['record' => $this->role->id])
        ->assertSee($this->role->name)
        ->assertSee($this->role->guard_name);
});
