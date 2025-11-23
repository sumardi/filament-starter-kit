<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Settings\Roles\Schemas;

use App\Filament\Admin\Resources\Settings\Roles\Pages\EditRole;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('Role Information'))
                    ->description(__('Fill in the basic role information.'))
                    ->inlineLabel()
                    ->components([
                        TextInput::make('display_name')
                            ->label('Role Display Name')
                            ->required(),
                        TextInput::make('name')
                            ->label('Role Name')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->disabled(fn ($livewire): bool => $livewire instanceof EditRole),
                        Select::make('guard_name')
                            ->label(__('Guard name'))
                            ->options([
                                'web' => __('web'),
                            ])
                            ->default('web')
                            ->searchable()
                            ->required(),
                    ]),
            ]);
    }
}
