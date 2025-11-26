<?php

declare(strict_types=1);

use App\Filament\Pages\Auth\Login;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertAuthenticatedAs;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

it('can render the login page', function (): void {
    livewire(Login::class)
        ->assertSuccessful();
});

it('can open the url', function (): void {
    get('/user/login')
        ->assertSuccessful();
});

it('can open the route', function (): void {
    get(route('filament.user.auth.login'))
        ->assertSuccessful();
});

it('can redirect `/login` to the login page', function (): void {
    get('/login')
        ->assertRedirect('/user/login');
});

it('can authenticate with valid credentials', function (): void {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    livewire(Login::class)
        ->fillForm([
            'email' => 'test@example.com',
            'password' => 'password',
        ])
        ->call('authenticate')
        ->assertHasNoFormErrors();

    assertAuthenticatedAs($user);
});

it('cannot authenticate with invalid credentials', function (): void {
    User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    livewire(Login::class)
        ->fillForm([
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ])
        ->call('authenticate')
        ->assertHasFormErrors(['email']);

    assertGuest();
});

it('cannot authenticate with non-existent email', function (): void {
    livewire(Login::class)
        ->fillForm([
            'email' => 'nonexistent@example.com',
            'password' => 'password',
        ])
        ->call('authenticate')
        ->assertHasFormErrors(['email']);

    assertGuest();
});

it('requires email and password', function (): void {
    livewire(Login::class)
        ->fillForm([])
        ->call('authenticate')
        ->assertHasFormErrors(['email', 'password']);

    assertGuest();
});

it('redirects authenticated users to dashboard', function (): void {
    $user = User::factory()->create();

    actingAs($user)
        ->get('/user/login')
        ->assertRedirect('/user');
});
