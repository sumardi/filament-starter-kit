<?php

declare(strict_types=1);

use App\Filament\Admin\Resources\Settings\Roles\Pages\ListRoles;
use App\Models\Role;
use Filament\Actions\Testing\TestAction;

use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

it('can render the roles list page', function (): void {
    get(ListRoles::getUrl())
        ->assertSuccessful();
});

it('can list roles', function (): void {
    $roles = Role::factory()->count(3)->create();

    livewire(ListRoles::class)
        ->loadTable()
        ->assertCanSeeTableRecords($roles);
});

it('can search roles by name', function (): void {
    $role = Role::factory()->create(['display_name' => 'Editor', 'name' => 'editor']);
    Role::factory()->count(2)->create();

    livewire(ListRoles::class)
        ->loadTable()
        ->searchTable('Editor')
        ->assertCanSeeTableRecords([$role])
        ->assertCountTableRecords(1);
});

it('can delete a role', function (): void {
    $role = Role::factory()->create();

    livewire(ListRoles::class)
        ->callAction(TestAction::make('delete')->table($role));

    assertModelMissing($role);
});

it('cannot delete a role that is not deletable', function (): void {
    $role = Role::factory()->create(['is_deletable' => false]);

    livewire(ListRoles::class)
        ->loadTable()
        ->assertActionHidden(TestAction::make('delete')->table($role));

    assertModelExists($role);
});
