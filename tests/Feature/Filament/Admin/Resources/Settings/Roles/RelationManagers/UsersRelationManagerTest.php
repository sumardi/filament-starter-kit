<?php

declare(strict_types=1);

use App\Filament\Admin\Resources\Settings\RoleResource;
use App\Filament\Admin\Resources\Settings\Roles\Pages\ViewRole;
use App\Filament\Admin\Resources\Settings\Roles\RelationManagers\UsersRelationManager;
use App\Models\Role;
use App\Models\User;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Actions\Testing\TestAction;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Livewire\livewire;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->role = Role::factory()->create();
});

it('can list users in the relation manager', function (): void {
    $attachedUser = User::factory()->create()
        ->assignRole($this->role->name);

    livewire(UsersRelationManager::class, [
        'ownerRecord' => $this->role,
        'pageClass' => ViewRole::class,
    ])
        ->loadTable()
        ->assertCanSeeTableRecords([$attachedUser]);
});

it('can attach a user to the role', function (): void {
    $attachedUser = User::factory()->create();
    livewire(UsersRelationManager::class, [
        'ownerRecord' => $this->role,
        'pageClass' => ViewRole::class,
    ])
        ->callAction(TestAction::make(AttachAction::class)->table(), [
            'recordId' => $attachedUser->id,
        ]);

    expect($this->role->users()->whereKey($attachedUser)->exists())->toBeTrue();
});

it('can detach a user from the role', function (): void {
    $attachedUser = User::factory()->create();
    $this->role->users()->attach($attachedUser);

    livewire(UsersRelationManager::class, [
        'ownerRecord' => $this->role,
        'pageClass' => RoleResource::class,
    ])
        ->callAction(TestAction::make(DetachAction::class)->table($attachedUser));

    expect($this->role->users()->whereKey($attachedUser)->exists())->toBeFalse();
});
