<?php

declare(strict_types=1);

use App\Filament\Admin\Resources\Settings\Users\Pages\ViewUser;
use App\Models\User;

use function Pest\Livewire\livewire;

it('can render the view user page', function (): void {
    $user = User::factory()->create();

    livewire(ViewUser::class, ['record' => $user->id])
        ->assertSuccessful();
});

it('displays the correct user details', function (): void {
    $user = User::factory()->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);

    livewire(ViewUser::class, ['record' => $user->id])
        ->assertSee('John Doe')
        ->assertSee('john@example.com');
});
