<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Settings\ActivityLogs\Tables;

use App\Filament\Admin\Resources\Settings\Roles\RoleResource;
use App\Filament\Admin\Resources\Settings\Users\UserResource;
use App\Models\Activity;
use App\Models\Role;
use App\Models\User;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

final class ActivityLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordAction('view')
            ->columns([
                TextColumn::make('log_name')
                    ->label(__('Log Name'))
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->label(__('Description'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('resource')
                    ->label(__('Resource'))
                    ->getStateUsing(fn (Activity $record): string => class_basename($record->subject_type ?? ''))
                    ->badge(),
                TextColumn::make('subject')
                    ->label(__('Subject'))
                    ->formatStateUsing(function (Activity $record): mixed {
                        if (! $record->subject) {
                            return __('N/A');
                        }

                        // Handle different model types with different name attributes
                        return match ($record->subject_type) {
                            User::class => $record->subject->getAttribute('name'),
                            Role::class => $record->subject->getAttribute('display_name'),
                            default => __('Unknown'),
                        };
                    })
                    ->url(function (Activity $record): ?string {
                        $subject = $record->subject;
                        if (! $subject) {
                            return null;
                        }

                        // Handle different model types with different name attributes
                        return match ($record->subject_type) {
                            User::class => UserResource::getUrl('view', ['record' => $subject]),
                            Role::class => RoleResource::getUrl('view', ['record' => $subject]),
                            default => null,
                        };
                    })
                    ->openUrlInNewTab()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('causer')
                    ->label(__('Performed By'))
                    ->placeholder(__('System'))
                    ->formatStateUsing(function (Activity $record): mixed {
                        if (! $record->causer) {
                            return __('System');
                        }

                        // Handle different model types with different name attributes
                        return match ($record->causer_type) {
                            User::class => $record->causer->getAttribute('name'),
                            Role::class => $record->causer->getAttribute('display_name'),
                            default => __('Unknown'),
                        };
                    })
                    ->url(function (Activity $record): ?string {
                        if (! $record->causer) {
                            return null;
                        }

                        $causer = $record->causer;

                        return match ($record->causer_type) {
                            User::class => UserResource::getUrl('view', ['record' => $causer]),
                            Role::class => RoleResource::getUrl('view', ['record' => $causer]),
                            default => null,
                        };
                    })
                    ->openUrlInNewTab()
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->sortable()
                    ->toggleable()
                    ->dateTime(),
            ])
            ->filters([
                SelectFilter::make('log_name')
                    ->label(__('Log Name'))
                    ->options([
                        'default' => __('Default'),
                    ]),
                SelectFilter::make('subject_type')
                    ->options([
                        User::class => __('User'),
                        Role::class => __('Role'),
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
