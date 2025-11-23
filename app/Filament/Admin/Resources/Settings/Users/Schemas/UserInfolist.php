<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Settings\Users\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('Account Information'))
                    ->description(__('View and manage the basic account details for this user.'))
                    ->inlineLabel()
                    ->columns()
                    ->schema(self::getAccountInformationSchema()),
            ]);
    }

    /**
     * @return array<Component>
     **/
    public static function getAccountInformationSchema(): array
    {
        return [
            Group::make([
                TextEntry::make('name')
                    ->label(__('Name')),
                TextEntry::make('email')
                    ->label(__('Email')),
                TextEntry::make('roles.display_name')
                    ->label(__('Roles'))
                    ->badge(),
            ]),
            Group::make([
                TextEntry::make('email_verified_at')
                    ->label(__('Email Verified At'))
                    ->placeholder(__('Not verified'))
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->label(__('Created At'))
                    ->placeholder(__('-'))
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->label(__('Updated At'))
                    ->placeholder(__('-'))
                    ->dateTime(),
            ]),
        ];
    }
}
