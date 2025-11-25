<?php

declare(strict_types=1);

use App\Filament\Admin\Resources\Settings\Roles\Pages\EditRole;
use App\Filament\Admin\Resources\Settings\Roles\RelationManagers\UsersRelationManager;
use App\Models\Permission;
use App\Models\Role;

use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $this->role = Role::factory()->create();
});

it('can render the edit role page', function (): void {
    get(EditRole::getUrl(['record' => $this->role]))
        ->assertSuccessful();
});

it('can retrieve role data on mount', function (): void {
    livewire(EditRole::class, ['record' => $this->role->id])
        ->assertSchemaStateSet([
            'name' => $this->role->name,
        ]);
});

it('can update a role', function (): void {
    $newName = 'updated_role';

    livewire(EditRole::class, ['record' => $this->role->id])
        ->fillForm([
            'display_name' => $newName,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($this->role->fresh())
        ->display_name->toBe($newName);
});

it('cannot update role name', function (): void {
    $newName = 'updated_role';

    livewire(EditRole::class, ['record' => $this->role->id])
        ->fillForm([
            'name' => $newName,
        ])
        ->call('save')
        ->assertFormFieldDisabled('name')
        ->assertHasNoFormErrors();

    expect($this->role->fresh())
        ->name->toBe($this->role->name);
});

it('validates required fields', function (): void {
    livewire(EditRole::class, ['record' => $this->role->id])
        ->fillForm([
            'name' => '',
        ])
        ->call('save')
        ->assertHasFormErrors(['name' => 'required']);
});

it('validates unique role name', function (): void {
    $existingRole = Role::factory()->create(['name' => 'Existing Role']);

    livewire(EditRole::class, ['record' => $this->role->id])
        ->fillForm([
            'name' => $existingRole->name,
        ])
        ->call('save')
        ->assertHasFormErrors(['name' => 'unique']);
});

it('can load users relation manager', function (): void {
    livewire(EditRole::class, [
        'record' => $this->role->id,
    ])
        ->assertSeeLivewire(UsersRelationManager::class);
});

it('can update role permissions', function (): void {
    $newPermission = Permission::query()->firstWhere(['name' => 'manage roles']);

    livewire(EditRole::class, ['record' => $this->role->id])
        ->fillForm([
            'permissions' => [$newPermission->id],
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($this->user->fresh())
        ->can($newPermission->name)->toBeTrue();
});
