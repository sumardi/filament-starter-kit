<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

final class User extends Authenticatable implements FilamentUser, HasAvatar, HasMedia
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;

    use HasRoles;
    use InteractsWithMedia;
    use Notifiable;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = ['avatar'];

    /**
     * Determine if the user can access the panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        $permissions = Permission::query()->where('group', 'admin')->pluck('name');

        return match ($panel->getId()) {
            'admin' => $this->hasAnyPermission($permissions),
            'user' => true,
            default => false,
        };
    }

    /**
     * Get the avatar URL for Filament.
     */
    public function getFilamentAvatarUrl(): string
    {
        return filled($this->getFirstMediaUrl('avatar')) ? $this->getFirstMediaUrl('avatar') : $this->defaultAvatarUrl();
    }

    /**
     * Register the media collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
            ->singleFile();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's avatar.
     *
     * @return Attribute<string, never>
     */
    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->getFilamentAvatarUrl(),
        );
    }

    /**
     * Get the default avatar URL.
     */
    private function defaultAvatarUrl(): string
    {
        $name = mb_trim(collect(explode(' ', ($this->name ?? 'User')))->map(fn ($segment): string => mb_substr($segment, 0, 1))->join(' '));

        return 'https://ui-avatars.com/api/?name='.urlencode($name).'&length=2&color=7B3306&background=FD9A00';
    }
}
