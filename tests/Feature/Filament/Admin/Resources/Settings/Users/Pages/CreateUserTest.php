<?php

declare(strict_types=1);

use App\Filament\Admin\Resources\Settings\Users\Pages\CreateUser;
use App\Models\User;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

it('can render the create user page', function (): void {
    get(route('filament.admin.resources.settings.users.create'))
        ->assertSuccessful();
});

it('can create a new user', function (): void {
    livewire(CreateUser::class)
        ->fillForm([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseHas(User::class, [
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);
});

it('validates required fields', function (): void {
    livewire(CreateUser::class)
        ->fillForm([
            'name' => '',
            'email' => '',
            'password' => '',
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);
});

it('validates email format', function (): void {
    livewire(CreateUser::class)
        ->fillForm([
            'email' => 'invalid-email',
        ])
        ->call('create')
        ->assertHasFormErrors([
            'email' => 'email',
        ]);
});

it('validates unique email', function (): void {
    User::factory()->create(['email' => 'existing@example.com']);

    livewire(CreateUser::class)
        ->fillForm([
            'email' => 'existing@example.com',
        ])
        ->call('create')
        ->assertHasFormErrors([
            'email' => 'unique',
        ]);
});

it('hashes the password before saving', function (): void {
    livewire(CreateUser::class)
        ->fillForm([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'secret123',
        ])
        ->call('create');

    $user = User::query()->where('email', 'jane@example.com')->first();
    expect($user->password)->not->toBe('secret123');
    expect(password_verify('secret123', (string) $user->password))->toBeTrue();
});
