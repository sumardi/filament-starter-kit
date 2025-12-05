<?php

declare(strict_types=1);

namespace App\Filament\Admin\Pages\Settings;

use App\Helpers\LocaleFinder;
use App\Settings\PreferencesSettings;
use BackedEnum;
use DateTimeImmutable;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Override;
use UnitEnum;

final class Preferences extends SettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::ComputerDesktop;

    protected static string $settings = PreferencesSettings::class;

    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $slug = 'settings/preferences';

    #[Override]
    public function form(Schema $schema): Schema
    {
        $timezoneOptions = collect(timezone_identifiers_list())
            ->mapWithKeys(fn (string $timezone): array => [$timezone => $timezone]);

        return $schema
            ->columns(1)
            ->components([
                Section::make(__('Preferences'))
                    ->description(__('Configure locale, datetime and timezone preferences for the application.'))
                    ->inlineLabel()
                    ->components([
                        Select::make('locale')
                            ->options(LocaleFinder::getAvailableLocales())
                            ->required()
                            ->searchable(),
                        Select::make('timezone')
                            ->options($timezoneOptions)
                            ->required()
                            ->searchable(),
                        Radio::make('dateFormat')
                            ->live()
                            ->formatStateUsing(fn (string $state): string => in_array($state, ['Y-m-d', 'd-m-Y', 'm-d-Y']) ? $state : 'custom')
                            ->options([
                                'Y-m-d' => '2017-04-17',
                                'd-m-Y' => '17-04-2017',
                                'm-d-Y' => '04-17-2017',
                                'custom' => __('Custom'),
                            ])
                            ->required(),
                        TextInput::make('customDateFormat')
                            ->live()
                            ->hiddenLabel()
                            ->visible(fn (callable $get): bool => $get('dateFormat') === 'custom')
                            ->formatStateUsing(fn (callable $get): mixed => $get('dateFormat') === 'custom' ? resolve(PreferencesSettings::class)->dateFormat : $get('dateFormat'))
                            ->required()
                            ->suffix(function (callable $get): string {
                                /** @var string $customDateFormat */
                                $customDateFormat = $get('customDateFormat');

                                return new DateTimeImmutable('2017-04-17')->format($customDateFormat);
                            })
                            ->inlineSuffix(),
                        Radio::make('timeFormat')
                            ->live()
                            ->formatStateUsing(fn (string $state): string => in_array($state, ['H:i:s', 'h:i:s A', 'h:i A', 'H:i']) ? $state : 'custom')
                            ->options([
                                'H:i:s' => '23:45:00',
                                'h:i:s A' => '11:45:00 PM',
                                'h:i A' => '11:45 PM',
                                'H:i' => '23:45',
                                'custom' => __('Custom'),
                            ])
                            ->required(),
                        TextInput::make('customTimeFormat')
                            ->live()
                            ->hiddenLabel()
                            ->visible(fn (callable $get): bool => $get('timeFormat') === 'custom')
                            ->formatStateUsing(fn (callable $get): mixed => $get('timeFormat') === 'custom' ? resolve(PreferencesSettings::class)->timeFormat : $get('timeFormat'))
                            ->required()
                            ->suffix(function (callable $get): string {
                                /** @var string $customTimeFormat */
                                $customTimeFormat = $get('customTimeFormat');

                                return new DateTimeImmutable('2017-04-17 23:45:00')->format($customTimeFormat);
                            })
                            ->inlineSuffix(),
                    ]),
            ]);
    }

    #[Override]
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['dateFormat'] === 'custom') {
            $data['dateFormat'] = $data['customDateFormat'];
        }

        if ($data['timeFormat'] === 'custom') {
            $data['timeFormat'] = $data['customTimeFormat'];
        }

        return $data;
    }
}
