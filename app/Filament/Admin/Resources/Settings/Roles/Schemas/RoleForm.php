<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Settings\Roles\Schemas;

use App\Filament\Admin\Resources\Settings\Roles\Pages\EditRole;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

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
                Section::make(__('Permissions'))
                    ->description(__('Choose which permissions this role should grant.'))
                    ->components([
                        Fieldset::make(__('Admin Access'))
                            ->columns(1)
                            ->components([
                                CheckboxList::make('permissions')
                                    ->hiddenLabel()
                                    ->bulkToggleable()
                                    ->searchable()
                                    ->relationship('permissions', 'name',
                                        fn (Builder $query): Builder => $query->whereGroup('admin')->latest()
                                    )
                                    ->columns(),
                            ]),
                    ]),
            ]);
    }
}
