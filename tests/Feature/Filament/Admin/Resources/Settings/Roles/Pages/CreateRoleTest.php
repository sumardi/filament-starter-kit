<?php

declare(strict_types=1);

use App\Filament\Admin\Resources\Settings\Roles\Pages\CreateRole;
use App\Models\Permission;
use App\Models\Role;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

it('can render the create role page', function (): void {
    get(CreateRole::getUrl())
        ->assertSuccessful();
});

it('can create a role', function (): void {
    livewire(CreateRole::class)
        ->fillForm([
            'display_name' => 'Editor',
            'name' => 'editor',
            'guard_name' => 'web',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseHas(Role::class, [
        'name' => 'editor',
    ]);
});

it('validates required fields when creating a role', function (): void {
    livewire(CreateRole::class)
        ->call('create')
        ->assertHasFormErrors([
            'display_name' => 'required',
            'name' => 'required',
        ]);
});

it('validates unique name when creating a role', function (): void {
    Role::factory()->create(['name' => 'editor']);

    livewire(CreateRole::class)
        ->fillForm([
            'display_name' => 'Editor',
            'name' => 'editor',
            'guard_name' => 'web',
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'unique',
        ]);
});

it('can create a role with permissions', function (): void {
    $permission = Permission::query()->firstWhere('name', 'manage users');
    livewire(CreateRole::class)
        ->fillForm([
            'display_name' => 'Editor',
            'name' => 'editor',
            'guard_name' => 'web',
            'permissions' => [$permission->id],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseHas(Role::class, [
        'name' => 'editor',
    ]);
    expect($this->user->fresh())
        ->can($permission->name)->toBeTrue();
});
