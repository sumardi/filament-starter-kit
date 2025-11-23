<?php

declare(strict_types=1);

use App\Filament\Admin\Resources\Settings\Users\Pages\ListUsers;
use App\Models\User;
use Filament\Actions\Testing\TestAction;

use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

it('can render the list users page', function (): void {
    User::factory()->count(3)->create();

    get(ListUsers::getUrl())
        ->assertSuccessful();
});

it('can list users', function (): void {
    $users = User::factory()->count(3)->create();

    livewire(ListUsers::class)
        ->loadTable()
        ->assertCanSeeTableRecords($users);
});

it('can search users by name', function (): void {
    $user = User::factory()->create();

    livewire(ListUsers::class)
        ->loadTable()
        ->searchTable($user->name)
        ->assertCanSeeTableRecords([$user])
        ->assertCountTableRecords(1);
});

it('can search users by email', function (): void {
    $user = User::factory()->create(['email' => 'john@example.com']);

    livewire(ListUsers::class)
        ->loadTable()
        ->searchTable('john@example.com')
        ->assertCanSeeTableRecords([$user])
        ->assertCountTableRecords(1);
});

it('can delete a user', function (): void {
    $user = User::factory()->create();

    livewire(ListUsers::class)
        ->selectTableRecords([$user])
        ->callAction(TestAction::make('delete')->table()->bulk())
        ->assertNotified();

    assertModelMissing($user);
});
