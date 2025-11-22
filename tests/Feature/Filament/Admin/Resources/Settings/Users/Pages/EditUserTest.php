<?php

declare(strict_types=1);

use App\Filament\Admin\Resources\Settings\Users\Pages\EditUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

uses(RefreshDatabase::class);

it('can render the edit user page', function (): void {
    $user = User::factory()->create();

    get(EditUser::getUrl(['record' => $user]))
        ->assertSuccessful();
});

it('can retrieve data for the edit form', function (): void {
    $user = User::factory()->create();

    livewire(EditUser::class, ['record' => $user->id])
        ->assertSchemaStateSet([
            'name' => $user->name,
            'email' => $user->email,
        ]);
});

it('can update a user', function (): void {
    $user = User::factory()->create();
    $newData = [
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
    ];

    livewire(EditUser::class, ['record' => $user->id])
        ->fillForm($newData)
        ->call('save')
        ->assertHasNoFormErrors();

    expect($user->refresh())
        ->name->toBe($newData['name'])
        ->email->toBe($newData['email']);
});

it('validates required fields when updating', function (): void {
    $user = User::factory()->create();

    livewire(EditUser::class, ['record' => $user->id])
        ->fillForm([
            'name' => '',
            'email' => '',
        ])
        ->call('save')
        ->assertHasFormErrors(['name', 'email']);
});

it('validates email format when updating', function (): void {
    $user = User::factory()->create();

    livewire(EditUser::class, ['record' => $user->id])
        ->fillForm([
            'name' => 'Valid Name',
            'email' => 'invalid-email',
        ])
        ->call('save')
        ->assertHasFormErrors(['email']);
});

it('validates unique email when updating', function (): void {
    $existingUser = User::factory()->create(['email' => 'existing@example.com']);
    $userToUpdate = User::factory()->create();

    livewire(EditUser::class, ['record' => $userToUpdate->id])
        ->fillForm([
            'name' => 'Valid Name',
            'email' => $existingUser->email,
        ])
        ->call('save')
        ->assertHasFormErrors(['email' => 'unique']);
});
