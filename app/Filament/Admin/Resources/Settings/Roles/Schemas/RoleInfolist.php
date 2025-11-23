<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Settings\Roles\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class RoleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('Role Information'))
                    ->description(__('User roles allow for certain actions and access levels within the application.'))
                    ->columns()
                    ->components([
                        TextEntry::make('display_name')
                            ->label(__('Role Display Name')),
                        TextEntry::make('name')
                            ->label(__('Role Name')),
                        TextEntry::make('guard_name')
                            ->label(__('Guard Name'))
                            ->badge(),
                    ]),
            ]);
    }
}
