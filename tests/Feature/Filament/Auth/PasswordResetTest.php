<?php

declare(strict_types=1);

use App\Models\User;
use Filament\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Filament\Auth\Pages\PasswordReset\RequestPasswordReset;
use Filament\Auth\Pages\PasswordReset\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

it('can render reset password page', function (): void {
    livewire(ResetPassword::class)
        ->assertOk();
});

it('can render request password reset page', function (): void {
    livewire(RequestPasswordReset::class)
        ->assertOk();
});

it('can request a password reset link', function (): void {
    $user = User::factory()->create();
    Notification::fake();

    livewire(RequestPasswordReset::class)
        ->fillForm([
            'email' => $user->email,
        ])
        ->call('request')
        ->assertHasNoFormErrors()
        ->assertNotified();
    Notification::assertSentTo($user, ResetPasswordNotification::class);

    assertDatabaseHas('password_reset_tokens', [
        'email' => $user->email,
    ]);
});

it('does not send a reset link to an invalid email', function (): void {
    Notification::fake();
    livewire(RequestPasswordReset::class)
        ->fillForm([
            'email' => 'nonexistent@example.com',
        ])
        ->call('request');
    Notification::assertNothingSent();
});

it('can display the password reset form with a valid token', function (): void {
    $user = User::factory()->create();
    $token = Password::createToken($user);

    livewire(ResetPassword::class, ['token' => $token])
        ->assertOk();
});

it('can reset the password with a valid token', function (): void {
    $user = User::factory()->create();
    $token = Password::createToken($user);
    $newPassword = 'NewPassword123!';

    livewire(ResetPassword::class, ['token' => $token])
        ->fillForm([
            'email' => $user->email,
            'password' => $newPassword,
            'passwordConfirmation' => $newPassword,
        ])
        ->call('resetPassword')
        ->assertHasNoFormErrors();

    $this->assertTrue(auth()->attempt([
        'email' => $user->email,
        'password' => $newPassword,
    ]));
});

it('does not open the reset password link an invalid token', function (): void {
    get('/user/password-reset/reset', ['token' => 'invalid-token'])
        ->assertForbidden();
});

it('does not reset the password with mismatched confirmation', function (): void {
    $user = User::factory()->create();
    $token = Password::createToken($user);

    livewire(ResetPassword::class, ['token' => $token])
        ->fillForm([
            'email' => $user->email,
            'password' => 'NewPassword123!',
            'passwordConfirmation' => 'DifferentPassword123!',
        ])
        ->call('resetPassword')
        ->assertHasFormErrors(['password']);
});
