<?php

declare(strict_types=1);

use App\Filament\User\Pages\MyProfile;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use function Pest\Livewire\livewire;

it('can render profile page', function (): void {
    livewire(MyProfile::class)
        ->assertOk();
});

it('displays user name and email', function (): void {
    livewire(MyProfile::class)
        ->assertSeeHtml($this->user->name)
        ->assertSeeHtml($this->user->email);
});

it('can update profile name', function (): void {
    livewire(MyProfile::class)
        ->fillForm(['name' => 'New Name'])
        ->call('save')
        ->assertHasNoErrors();

    expect($this->user->fresh()->name)->toBe('New Name');
});

it('validates required name', function (): void {
    livewire(MyProfile::class)
        ->fillForm(['name' => ''])
        ->call('save')
        ->assertHasErrors(['data.name' => 'required']);
});

it('validates email format', function (): void {
    livewire(MyProfile::class)
        ->fillForm(['email' => 'invalid-email'])
        ->call('save')
        ->assertHasErrors(['data.email' => 'email']);
});

it('ensures email is unique among other users', function (): void {
    User::factory()->create(['email' => 'taken@example.com']);

    livewire(MyProfile::class)
        ->fillForm(['email' => 'taken@example.com'])
        ->call('save')
        ->assertHasErrors(['data.email' => 'unique']);
});

it('can upload avatar', function (): void {
    Storage::fake('public');

    $file = UploadedFile::fake()->image('avatar.jpg', 300, 300);

    livewire(MyProfile::class)
        ->fillForm(['avatar' => $file])
        ->call('save')
        ->assertHasNoFormErrors();

    $this->user->refresh();

    expect($this->user->getFirstMedia('avatar'))
        ->not->toBeNull();
});
