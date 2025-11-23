<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Settings\Roles\RelationManagers;

use App\Filament\Admin\Resources\Settings\Users\Pages\EditUser;
use App\Filament\Admin\Resources\Settings\Users\Schemas\UserInfolist;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Override;

final class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    #[Override]
    public function isReadOnly(): bool
    {
        return false;
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components(UserInfolist::getAccountInformationSchema());
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->slideOver()
                    ->extraModalFooterActions([
                        Action::make('edit')
                            ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                            ->url(fn (User $record): string => EditUser::getUrl(['record' => $record]))
                            ->openUrlInNewTab(),
                        DetachAction::make(),
                    ]),
                DetachAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ]);
    }
}
