<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\UserFactory;
use Filament\Auth\MultiFactor\App\Contracts\HasAppAuthentication;
use Filament\Auth\MultiFactor\App\Contracts\HasAppAuthenticationRecovery;
use Filament\Auth\MultiFactor\Email\Contracts\HasEmailAuthentication;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property ?string $app_authentication_secret
 * @property ?array<string> $app_authentication_recovery_codes
 */
final class User extends Authenticatable implements FilamentUser, HasAppAuthentication, HasAppAuthenticationRecovery, HasAvatar, HasEmailAuthentication, HasMedia, MustVerifyEmail
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
        'app_authentication_secret',
        'app_authentication_recovery_codes',
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
     * This method should return the user's saved app authentication secret.
     */
    public function getAppAuthenticationSecret(): ?string
    {
        if (! array_key_exists('app_authentication_secret', $this->getAttributes())) {
            return self::query()
                ->whereKey($this->getKey())
                ->first(['app_authentication_secret'])?->app_authentication_secret;
        }

        return $this->app_authentication_secret;
    }

    /**
     * This method should save the user's app authentication secret.
     */
    public function saveAppAuthenticationSecret(?string $secret): void
    {
        $this->app_authentication_secret = $secret;
        $this->save();
    }

    /**
     * This method should return the user's app authentication holder name.
     */
    public function getAppAuthenticationHolderName(): string
    {
        return $this->email;
    }

    /**
     * This method should return the user's saved app authentication recovery codes.
     *
     * @return ?array<string>
     */
    public function getAppAuthenticationRecoveryCodes(): ?array
    {
        if (! array_key_exists('app_authentication_recovery_codes', $this->getAttributes())) {
            return self::query()
                ->whereKey($this->getKey())
                ->first(['app_authentication_recovery_codes'])?->app_authentication_recovery_codes;
        }

        return $this->app_authentication_recovery_codes;
    }

    /**
     * This method should save the user's app authentication recovery codes.
     *
     * @param  array<string> | null  $codes
     */
    public function saveAppAuthenticationRecoveryCodes(?array $codes): void
    {
        $this->app_authentication_recovery_codes = $codes;
        $this->save();
    }

    /**
     * This method should return true if the user has enabled email authentication.
     */
    public function hasEmailAuthentication(): bool
    {
        if (! array_key_exists('has_email_authentication', $this->getAttributes())) {
            return (bool) self::query()
                ->whereKey($this->getKey())
                ->value('has_email_authentication');
        }

        return $this->has_email_authentication;
    }

    /**
     * This method should save whether or not the user has enabled email authentication.
     */
    public function toggleEmailAuthentication(bool $condition): void
    {
        $this->has_email_authentication = $condition;
        $this->save();
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
            'app_authentication_secret' => 'encrypted',
            'app_authentication_recovery_codes' => 'encrypted:array',
            'email_verified_at' => 'datetime',
            'has_email_authentication' => 'boolean',
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
