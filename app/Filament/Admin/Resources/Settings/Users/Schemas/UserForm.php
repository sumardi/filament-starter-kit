<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Settings\Users\Schemas;

use App\Filament\Admin\Resources\Settings\Users\Pages\CreateUser;
use App\Filament\Admin\Resources\Settings\Users\Pages\EditUser;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

final class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('Account Information'))
                    ->description(__('Fill in the account information.'))
                    ->inlineLabel()
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Name'))
                            ->maxLength(255)
                            ->required(),
                        TextInput::make('email')
                            ->label(__('Email'))
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->email()
                            ->required(),
                    ]),
                Section::make(__('Password'))
                    ->description(__('Ensure the account is using a long, random password to stay secure.'))
                    ->inlineLabel()
                    ->schema([
                        TextInput::make('password')
                            ->label(__('New password'))
                            ->password()
                            ->required(fn ($livewire): bool => $livewire instanceof CreateUser)
                            ->revealable(filament()->arePasswordsRevealable())
                            ->rule(Password::default())
                            ->autocomplete('new-password')
                            ->dehydrated(fn ($state): bool => filled($state))
                            ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                            ->helperText(fn ($livewire): array|string|null => $livewire instanceof EditUser ? __('Leave blank to keep the current password.') : null),
                    ]),
                Section::make(__('Role'))
                    ->description(__('Assign one or more roles to the user.'))
                    ->inlineLabel()
                    ->schema([
                        Select::make('roles')
                            ->label(__('Role'))
                            ->multiple()
                            ->relationship('roles', 'display_name')
                            ->preload()
                            ->searchable(),
                    ]),
            ]);
    }
}
